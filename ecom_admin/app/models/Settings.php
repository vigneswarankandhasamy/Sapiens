<?php
	require_once 'Model.php';
	require_once 'FileUploader.php';
	require_once 'MultipleFileUploader.php';
	require_once 'config/config.php';
	require_once 'app/core/classes/PHPMailerAutoload.php';

	class Settings extends Model
	{	

		/*---------------------------------------------
						Company Info
		----------------------------------------------*/

		// Update Info

		function updateCompanyInfo($input,$image)
		{	
			$data 			= $this->cleanStringData($input);
			$validate_csrf  = $this->validateCSRF($data);
			if ($validate_csrf=="success") {
				$admin_id 		= $_SESSION["ecom_admin_id"];
				$curr 			= date("Y-m-d H:i:s");
				$image_q 		= "";
				$result 		= array();

				if ($image['is_uploaded']=="ok") {

					if(@$image['file_name']['header_logo']) {
						$image_q .= " logo ='".$image['file_name']['header_logo']."', ";
					}

					if(@$image['file_name']['footer_logo']) {
						$image_q .= " footer_logo ='".$image['file_name']['footer_logo']."', ";
					}

				} 

				$file_upload  = (($image['is_uploaded']=='ok')? 1 :  (($image['is_uploaded']=='empty')? 1  : 0  )  );

					if($file_upload) {
					
					$query = "UPDATE  ".COMPANNY_INFO." SET 
								company_name 				= '".($data['company_name'])."',
								contact_no_one 				= '".($data['contact_no_one'])."',
								contact_no_two 				= '".($data['contact_no_two'])."',
								email_one 					= '".($data['email_one'])."',
								email_two 					= '".($data['email_two'])."',
								address_one					= '".($data['address_one'])."',
								address_two 				= '".($data['address_two'])."',
								whatsapp_one 				= '".($data['whatsapp_one'])."',
								whatsapp_two 				= '".($data['whatsapp_two'])."',
								facebook     				= '".($data['facebook'])."',
								twitter     				= '".($data['twitter'])."',
								googleplus	 				= '".($data['googleplus'])."',
								rss 	 					= '".($data['rss'])."',
								pinterest 	 				= '".($data['pinterest'])."',
								linkedin 	 				= '".($data['linkedin'])."',
								youtube 	 				= '".($data['youtube'])."',
								instagram 					= '".($data['instagram'])."',
								gst_no 						= '".($data['gst_no'])."',
								registered_state_id 		= '".($data['reg_state'])."',
								vendor_payment_charges 		= '".($data['vendor_payment_charges'])."',
								vendor_payment_tax 			= '".($data['vendor_payment_tax'])."',
								vendor_shipping_charges 	= '".($data['vendor_shipping_charges'])."',
								vendor_shipping_tax 		= '".($data['vendor_shipping_tax'])."',
								maximum_order_value 		= '".($data['minimum_order_value'])."',
								single_vendor_shipping_cost = '".($data['single_vendor_shipping_cost'])."',
								multi_vendor_shipping_cost 	= '".($data['multi_vendor_shipping_cost'])."',
								order_shipping_tax 			= '".($data['order_shipping_tax'])."',
								".$image_q."
								status 						= '1',
								updated_at 					= '$curr' WHERE id='1' ";
				
					$this->exeQuery($query);
					$this->unSetCSRF($data['csrf_key']);

					$result['data_stored'] = 1;
					return json_encode($result);
				} else {
					$result['data_stored'] = 0;
					$result['error'] 	   = $image['error'];
					return json_encode($result); 
				}
			}else{
				return $validate_csrf;
			}
		}

		/*---------------------------------------------
						Admin Profile
		----------------------------------------------*/

		// Update Profile

		function updateAdminProfile($input)
		{	
			$data 			= $this->cleanStringData($input);
			$validate_csrf  = $this->validateCSRF($data);
			if ($validate_csrf=="success") {
				$id 	= $_SESSION["ecom_admin_id"];
				$check 	= $this->check_query(USERS_TBL,"id"," (email='".$data['email']."' OR mobile = '".$data['mobile']."') AND id!='".$id."'  ");
				if($check==0) {
					$admin_id 		= $_SESSION["ecom_admin_id"];
					$curr 			= date("Y-m-d H:i:s");
					$save_as_draft 	= isset($data['save_as_draft']) ? 1 : 0;
					$normal_pass    = $this->mobileToken(8);
					$h_pass         = $this->encryptPassword($normal_pass);
					$name_string    = str_replace(' ', '_', $data['name']);
	                $token          = strtolower($name_string);
					$query 			= "UPDATE  ".USERS_TBL." SET 
								name 					= '".$this->cleanString($data['name'])."',
								mobile 					= '".$this->cleanString($data['mobile'])."',
								email 					= '".$this->cleanString($data['email'])."',					
								updated_at 				= '$curr' WHERE id='$id' ";
				
					$this->exeQuery($query);
					$this->unSetCSRF($data['csrf_key']);
					return 1;
				} else {
					return "User mobile number or email address already exists";
				}	
			}else{
				return $validate_csrf;
			}
		}

		// Change Password

		function changePasswordAdmin($input)
		{	
			$validate_csrf  = $this->validateCSRF($input);
			if ($validate_csrf=="success") {
				$id 	= $_SESSION["ecom_admin_id"];
				$check 	= $this->check_query(USERS_TBL,"id", " id= '".$_SESSION['ecom_admin_id']."' AND
                      password= '".$this->encryptPassword($input['password'])."' ");
				if($check==1) {
					$curr 			= date("Y-m-d H:i:s");
					$normal_pass    = $input['new_password'];
					$h_pass         = $this->encryptPassword($normal_pass);
					$query 			= "UPDATE  ".USERS_TBL." SET 
								password 				= '".$h_pass."',
								normal_pass 			= '".$normal_pass."',
								password_update 		= '$curr',					
								updated_at 				= '$curr' WHERE id='$id' ";
					$this->exeQuery($query);
					$this->unSetCSRF($input['csrf_key']);
					return 1;
				} else {
					return "Current password is wrong";
				}	
			}else{
				return $validate_csrf;
			}
		}

		/*--------------------------------------------- 
					Tax Class Settings
		----------------------------------------------*/ 	

		function manageTaxClass()
 	  	{
 	  		$layout = "";
	    	$q 	 = "SELECT id,tax_class,cgst,sgst,igst,status FROM ".TAX_CLASSES_TBL." WHERE delete_status='0'  ORDER BY id ASC" ;
 		    $exe = $this->exeQuery($q);
 		    if(mysqli_num_rows($exe)>0){
 		    	$i=1;
 		    	while($list = mysqli_fetch_array($exe)){
		    		$status 	  = (($list['status']==1) ? "Active" : "Inactive"); 
		    		$status_c 	  = (($list['status']==1) ? "checked" : " ");
					$status_class = (($list['status']==1) ? "text-success" : "text-danger");
					$td_data = " data-formclass='edit_tax_class' data-form='editTax' data-option='".$this->encryptData($list['id'])."'";
		    		$layout .= "
		    				<tr class='nk-tb-item'>
		    					<td class='nk-tb-col openEditTax' $td_data>
	                                ".$i."
	                            </td>
	                            <td class='nk-tb-col openEditTax' $td_data>
	                                ".$list['id']."
	                            </td>
	                            <td class='nk-tb-col tb-col-md openEditTax' $td_data>
	                                <span class='text-primary'>".$this->publishContent($list['tax_class'])."</span>
 	                            </td>
 	                            <td class='nk-tb-col tb-col-mb openEditTax' $td_data>
	                                ".$list['cgst']."
 	                            </td>
 	                            <td class='nk-tb-col tb-col-mb openEditTax' $td_data>
                               		 ".$list['sgst']."
 	                            </td>
 	                           	<td class='nk-tb-col tb-col-mb openEditTax' $td_data>
	                                ".$list['igst']."
 	                            </td>
 	                            <td class='nk-tb-col tb-col-md'>
		                            <div class='custom-control custom-switch'>
	                                    <input type='checkbox' class='custom-control-input changeTaxStatus' data-option='".$this->encryptData($list['id'])."' value='0' id='status_".$i."' save_as_draft' $status_c>
	                                    <label class='custom-control-label ' for='status_".$i."'><span class='$status_class'>$status </span></label>
	                                </div>
	                        	</td>
 	                            <td class='nk-tb-col nk-tb-col-tools'>
	                                <ul class='nk-tb-actions gx-1'>
	                                    <li class='nk-tb-action-hidden'>
	                                        <button class='btn btn-trigger btn-icon deleteTax' data-formclass='edit_tax_class' data-form='editTax' data-option='".$this->encryptData($list['id'])."' ><em class='icon ni ni-trash-fill'></em></button>
	                                    </li>
	                                </ul>
                        	    </td>
	                        </tr>";
		    		$i++;
		    	}
 		    }
 		    return $layout;
 		}

		// Add Tax Classes

		function addTaxClasses($input)
		{
			$data 		   = $this->cleanStringData($input);
			$validate_csrf = $this->validateCSRF($data);
			if ($validate_csrf=="success") {
				$admin_id 		= $_SESSION["ecom_admin_id"];
				$curr 			= date("Y-m-d H:i:s");
				$query 			= "INSERT INTO ".TAX_CLASSES_TBL." SET 
							token 		= '".$this->hyphenize($data['tax_class'])."',
							tax_class 	= '".$data['tax_class']."',
							cgst 		= '".$this->cleanString($data['cgst'])."',
							sgst 		= '".$this->cleanString($data['sgst'])."',
							igst 		= '".$this->cleanString($data['igst'])."',
							status		='1',
							added_by	= '$admin_id',	
							created_at 	= '$curr',
							updated_at 	= '$curr' ";
				$exe = $this->exeQuery($query);
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

		// Change Tax Status

		function changeTaxStatus($data)
		{
			$data = $this->decryptData($data);
			$info = $this -> getDetails(TAX_CLASSES_TBL,"status"," id ='$data' ");
			if($info['status'] ==1){
				$query = "UPDATE ".TAX_CLASSES_TBL." SET status='0' WHERE id='$data' ";
			}else{
				$query = "UPDATE ".TAX_CLASSES_TBL." SET status='1' WHERE id='$data' ";
			}
			$up_exe = $this->exeQuery($query);
			//return $up_exe;
			if($up_exe){
				return 1;
			}
		}

		// Get Tax item Details

		function getTaxItemDetails($data)
		{
			$layout = "";
			$tax_id = $this->decryptData($data);
			$query  = "SELECT * FROM  ".TAX_CLASSES_TBL."  WHERE id='$tax_id' ";
			$exe 	= $this->exeQuery($query);

			if(mysqli_num_rows($exe) > 0) {
				while ($list = mysqli_fetch_array($exe)) {
					$layout .= "<input type='hidden' name='token' value='".$this->encryptData($list['id'])."' id='token'>
				                <div class='form-group'>
				                    <label class='form-label'>Tax Class Name
				                        <en>*</en>
				                    </label>
				                    <input type='text' name='tax_class' id='tax_class' value='".$list['tax_class']."'  class='form-control' placeholder='Enter Tax Classes Name' required>
				                </div>
				                <div class='form-group'>
				                    <label class='form-label'>CGST (in %) <en>*</en> </label>
				                    <input type='text' name='cgst' id='cgst' value='".$list['cgst']."'  class='form-control'  placeholder='Enter CGST (in %)' required>
				                </div>
				                <div class='form-group'>
				                    <label class='form-label'> SGST (in %)
				                        <en>*</en>
				                    </label>
				                    <input type='text' name='sgst' id='sgst' value='".$list['sgst']."' class='form-control' placeholder='Enter SGST (in %)' required>
				                </div>
				                <div class='form-group'>
				                    <label class='form-label'>IGST (in %)
				                        <en>*</en>
				                    </label>
				                    <input type='text' name='igst' id='igst' value='".$list['igst']."' class='form-control' placeholder='Enter IGST (in %)' required>
				                </div>
					            <div class='form-error'>
					            </div>
					            <div class='form-group'>
					                <p class='float-right model_pt'>
					                <button type='button' class='btn btn-light close_modal' data-modal_id='editTaxModal'>Cancel</button>
					                <button type='submit' class='btn btn-primary'>Submit</button>
					                </p>
					            </div>";
				}
			}

			return $layout;
		}

		// Edit Tax Classes

		function editTaxClasses($data)
		{
			if(isset($_SESSION[$data['csrf_key']])){
				if($this->cleanString($data['csrf_token']) == $_SESSION[$data['csrf_key']]){
					$admin_id 		= $_SESSION["ecom_admin_id"];
					$tax_id 		= $this->decryptData($data["token"]);
					$curr 			= date("Y-m-d H:i:s");
					$query 			= "UPDATE ".TAX_CLASSES_TBL." SET 
								token 		= '".$this->hyphenize($data['tax_class'])."',
								tax_class 	= '".$this->cleanString($data['tax_class'])."',
								cgst 		= '".$this->cleanString($data['cgst'])."',
								sgst 		= '".$this->cleanString($data['sgst'])."',
								igst 		= '".$this->cleanString($data['igst'])."',
								updated_at 	= '$curr' WHERE id='$tax_id' ";
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

		// Update the delete status

		function deleteTax($data)
		{
			$data = $this->decryptData($data);
			$info = $this -> getDetails(TAX_CLASSES_TBL,"delete_status"," id ='$data' ");
			if($info['delete_status'] ==1){
				$query = "UPDATE ".TAX_CLASSES_TBL." SET delete_status='0' WHERE id='$data' ";
			}else{
				$query = "UPDATE ".TAX_CLASSES_TBL." SET delete_status='1' WHERE id='$data' ";
			}
			$up_exe = $this->exeQuery($query);
			if($up_exe){
				return 1;
			}
		}

		/*--------------------------------------------- 
					User Management
		----------------------------------------------*/

		// Manage Tax Classes

		function manageUsers()
	  	{
	  		$layout = "";
		    $q 		= "SELECT id,name,mobile,email,is_super_admin,invite_status,status,is_draft FROM ".USERS_TBL." WHERE delete_status='0'  ORDER BY id DESC" ;
		    $exe 	= $this->exeQuery($q);
		    if(mysqli_num_rows($exe)>0){
		    	$i=1;
		    	while($list = mysqli_fetch_array($exe)){
		    		$status 	  		= (($list['status']==1) ? "Active" : "Inactive"); 
		    		$status_c     		= (($list['status']==1) ? "checked" : " ");
					$status_class 		= (($list['status']==1) ? "text-success" : "text-danger");
					$user_type    		= (($list['is_super_admin']==1) ? "Super Admin" : "Employee");
					// Draft Status
					$draft_status       = (($list['is_draft']==1) ? "Draft" : "Published"); 
					$draft_status_c     = (($list['is_draft']==1) ? "checked" : ""); 
					$draft_status_class = (($list['is_draft']==1) ? "text-warning" : "text-success"); 
					$is_draft_row       = (($list['is_draft']==1) ? "draft_item" : ""); 
					$invite             = (($list['invite_status']== 0) ? "Send Invite" : "Resend Invite");


					if ($list['is_draft']==1) {
						$draft_action 		= "<button type='button' data-option='".$this->encryptData($list['id'])."' class='btn btn-warning btn-dim btn-sm publishUser' ><em class='icon ni ni-check-thick'></em> Publish</button>";
						$delete_class 		= 'deleteUser';
						$delete_class_hover = 'Delete User';

					}else{
						$draft_action 	    = "<div class='tb-tnx-status'>
	                            	<span class='badge badge-dot text-success cursor_pointer'> Published </span>
	                            </div>";
	                    $delete_class 		= 'trashUser';
	                    $delete_class_hover = 'Trash User';
	                    
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

					$td_data = " data-data_id='".$list['id']."' data-data_link='users/edit' ";

		    		$layout .= "
		    				<tr class='nk-tb-item $is_draft_row'>
		    					<td class='nk-tb-col td_click' $td_data>
	                                ".$i."
	                            </td>
	                            <td class='nk-tb-col td_click' $td_data>
	                                ".$list['id']."
	                            </td>
	                            <td class='nk-tb-col tb-col-md td_click' $td_data>
	                                <span class='text-primary'>".$this->publishContent($list['name'])."</span>
	                            </td>
	                            <td class='nk-tb-col tb-col-mb td_click' $td_data>
	                                ".$list['mobile']."
	                            </td>
	                            <td class='nk-tb-col tb-col-mb td_click' $td_data>
	                                ".$list['email']."
	                            </td>
	                           	<td class='nk-tb-col tb-col-mb td_click' $td_data>
	                                ".$user_type."
	                            </td>
	                            <td class='nk-tb-col td_click' $td_data>
                               		$draft_action
                            	</td>
                            	<td class='nk-tb-col tb-col-md'>
                            	<a href='javascript:void();'   data-id='".$this->encryptData($list['id'])."'  class='btn btn-warning btn-sm sendInvite'> ".$invite." </a>
                            	</td>
                            	<td class='nk-tb-col tb-col-md'>
	                            <div class='custom-control custom-switch'>
	                                <input type='checkbox' class='custom-control-input changeUserStatus' data-option='".$this->encryptData($list['id'])."' value='0' id='status_".$i."' name='save_as_draft' $status_c>
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

		// Add user

		function addUser($input)
		{	
			$validate_csrf = $this->validateCSRF($input);
			if ($validate_csrf=="success") {
				$check 	= $this->check_query(USERS_TBL,"id"," (email='".$input['email']."' OR mobile = '".$input['mobile']."')  ");
				if($check==0) {
					$admin_id 		= $_SESSION["ecom_admin_id"];
					$curr 			= date("Y-m-d H:i:s");
					$save_as_draft  = isset($input['save_as_draft']) ? 1 : 0;
					$normal_pass    = $this->mobileToken(8);
					$h_pass         = $this->encryptPassword($normal_pass);
					$name_string    = str_replace(' ', '_', $input['name']);
	                $token          = strtolower($name_string);
					$query 			= "INSERT INTO ".USERS_TBL." SET 
								name 				    = '".$this->cleanString($input['name'])."',
								mobile 					= '".$this->cleanString($input['mobile'])."',
								email 					= '".$this->cleanString($input['email'])."',				
								status 					= '1',
								is_draft 				= '".$save_as_draft."',
								normal_pass				= '".$normal_pass."',
								password 				= '".$h_pass."',
								created_at 				= '$curr',
								updated_at 				= '$curr' ";
					$user_id =  $this->lastInserID($query);
					if($user_id){

						if(isset($input['permission'])) {
							$query_p   = "INSERT INTO ".PERMISSION_TBL." SET 
							   user_id = '".$user_id."'";
							
							$permissions_array = $input["permission"];
							if(count($permissions_array)>0) {
								foreach ($permissions_array as $key => $value) {
									$query_p.=  ", ".$value." = '1' ";
								}
							}
							$query_p .= "
								, created_by 				= '$admin_id',	
								created_at 				= '$curr',
								updated_at 				= '$curr' ";
							 $this->exeQuery($query_p);
							$employee_id  = $user_id;
							$token 		  = $this->createEmployeeToken($token,$employee_id);
							$update_query = "UPDATE ".USERS_TBL." SET token = '$token' WHERE id='$employee_id' ";
							$update_token = $this->exeQuery($update_query);
							return 1;
						} else {
							return "Please select minimum one page paermission";
						}
						
					} else{
						return "Sorry!! Unexpected Error Occurred. Please try again.";
					} 

				} else {
					return "User mobile number or email address already exists";
				}	
			}else{
				return $validate_csrf;
			}
		}

		// Generate Employee Token

		function createEmployeeToken($name,$id)
		{
			$default = $this->hyphenize($name);
			$check   = $this ->check_query(USERS_TBL,"token"," token ='".$default."' AND id !='$id' ");
			if($check==0){
				return $default;
			}else{
				return $default.".".$id;
			}
		}

		// Get Permission Main Menu

		function permissionDataMaster()
	  	{
	  		$layout = "";
		    $q      ="SELECT id,permision_title,token,permission FROM ".PERMISSION_DATA_TBL." WHERE 1 AND main_menu='1' AND status='1' ORDER BY id ASC " ;
		    $query  = $this->exeQuery($q);
		    if(mysqli_num_rows($query)>0){
		    	$i=1;
		    	while($row = mysqli_fetch_array($query)){
		    		$list    = $this->editPagePublish($row);
		    		$layout .= "
		    			<div class='permission_parent'>
                    		<label class='checkbox-inline checkbox-success heading'>
                       		<input name='permission[]' value='".$list['permission']."' data-option='".$list['id']."' class='post_permission main_permission' id='main_".$list['id']."' type='checkbox'  > ".$list['permision_title']." </label>
                        	<span class='clearfix'></span>
                        	<div class='menu permission_menu' >
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

	  	// Get Sub menu permission

	  	function getSubMenuPermission($main_id)
	  	{
	  		$layout = "";
		    $q 		="SELECT id,permision_title,token,permission FROM ".PERMISSION_DATA_TBL." WHERE 1 AND main_menu='0' AND sub_menu_of='$main_id' AND status='1' ORDER BY id ASC " ;
		    $query 	= $this->exeQuery($q);
		    if(mysqli_num_rows($query)>0){
		    	$i=1;
		    	while($row = mysqli_fetch_array($query)){
		    		$list 	 = $this->editPagePublish($row);
		    		$layout .= "
		    			<li>
		    			 <label class='checkbox-inline checkbox-success sub_menu'>
		    			 <input name='permission[]' value='".$list['permission']."' data-option='".$main_id."' class='post_permission  sub_menu_permission sub_permission_".$main_id."' type='checkbox'>  ".$list['permision_title']."</label>
		    			 </li>
		    		";
	                $i++;
		    	}
		    }
		    return $layout;
	  	}

		// Edit User

		function editUser($input)
		{	
			$validate_csrf  = $this->validateCSRF($input);
			if ($validate_csrf=="success") {
				$id 	= $this->decryptData($input['session_token']);
				$check 	= $this->check_query(USERS_TBL,"id"," (email='".$input['email']."' OR mobile = '".$input['mobile']."') AND id!='".$id."'  ");
				if($check==0) {
					$info           = $this->getDetails(USERS_TBL,"*","id='".$id."'");
					$admin_id 		= $_SESSION["ecom_admin_id"];
					$curr 			= date("Y-m-d H:i:s");
					$save_as_draft 	= isset($input['save_as_draft']) ? 1 : 0;
					$normal_pass    = ( ($info['normal_pass']!="" )? $info['normal_pass'] : $this->mobileToken(8));
					$h_pass         = ( ($info['password']!="" )? $info['password'] : $this->encryptPassword($normal_pass));
					$name_string    = str_replace(' ', '_', $input['name']);
	                $token          = strtolower($name_string);
					$query 			= "UPDATE  ".USERS_TBL." SET 
								name 					= '".$this->cleanString($input['name'])."',
								mobile 					= '".$this->cleanString($input['mobile'])."',
								email 					= '".$this->cleanString($input['email'])."',					
								status 					= '1',
								is_draft 				= '".$save_as_draft."',
								normal_pass				= '".$normal_pass."',
								password 				= '".$h_pass."',
								updated_at 				= '$curr' WHERE id='$id' ";
					$exe 			   = $this->exeQuery($query);
					$cleanPermisiion   = $this->cleanEmployeePermissions($id);



					if($exe){
						$query_p = "UPDATE ".PERMISSION_TBL." SET ";
						// Insert Permission Columns
						if(isset($input['permission'])) {
							$permissions_array = $input["permission"];
							if(count($permissions_array)>0) {
								foreach ($permissions_array as $key => $value) {
									$query_p.=  "".$value." = '1', ";
								}
							}
							$query_p .= "
								created_by 				= '$admin_id',	
								updated_at 				= '$curr' WHERE user_id='".$id."' ";
							$exe_p = $this->exeQuery($query_p);

							if($exe_p) {
								unset($_SESSION['add_employee_key']);
								return 1;
							} else  {
								return "Sorry!! Unexpected Error Occurred While Update U/ser Permissions. Please try again.";
							}
						} else {
							return "Please select minimum one page paermission";
						}

					} else {
						return "Sorry!! Unexpected Error Occurred. Please try again.";
					} 

				} else {
					return "User mobile number or email address already exists";
				}	
			}else{
				return $validate_csrf;
			}
		}

	  	// Permission data master For edit

	  	function permissionDataMasterEdit($user_id)
	  	{
	  		$layout = "";
		    $q 		="SELECT id,permision_title,token,permission FROM ".PERMISSION_DATA_TBL." WHERE 1 AND main_menu='1' AND status='1' ORDER BY id ASC " ;
		    $query 	= $this->exeQuery($q);
		    if(mysqli_num_rows($query)>0){
		    	$i=1;
		    	while($row = mysqli_fetch_array($query)){
		    		$list 		   = $this->editPagePublish($row);
		    		$current_value = $this->getPermissionValue($user_id,$list['permission']);
	 				$checked 	   = (($current_value==1) ? "checked" : "");
		    		$layout .= "
	    				<div class='permission_parent'>
	                        <label class='checkbox-inline checkbox-success heading'>
	                        <input name='permission[]' value='".$list['permission']."' data-option='".$list['id']."' class='post_permission main_permission' id='main_".$list['id']."' type='checkbox'  $checked > ".$list['permision_title']." </label>
	                        <span class='clearfix'></span>
	                        <div class='menu permission_menu'>
	                        	<ul>
	                        		".$this->getSubMenuPermissionEdit($list['id'],$user_id)."
	                        	</ul>
	                        </div>
                        </div>";
	                $i++;
		    	}
		    }
		    return $layout;
	  	}

	  	// Get the permission value

	  	function getPermissionValue($user_id,$permission)
	  	{
	  		$q    = "SELECT $permission  FROM ".PERMISSION_TBL." WHERE user_id='$user_id' ";
	  		$query = $this->exeQuery($q);
		    if(@mysqli_num_rows($query)>0){
		    	while($row = mysqli_fetch_array($query)){
		    		$list = $this->editPagePublish($row);
		    		return $list[$permission];
		    	}
		    }
	  	}

  	  	// Get Sub menu permission

	  	function getSubMenuPermissionEdit($main_id,$user_id)
	  	{
	  		$layout = "";
		    $q 		="SELECT id,permision_title,token,permission FROM ".PERMISSION_DATA_TBL." WHERE 1 AND main_menu='0' AND sub_menu_of='$main_id'  AND status='1' ORDER BY id ASC " ;
		    $query = $this->exeQuery($q);
		    if(mysqli_num_rows($query)>0){
		    	$i=1;
		    	while($row = mysqli_fetch_array($query)){
		    		$list 		   = $this->editPagePublish($row);
		    		$current_value =  $this->getPermissionValue($user_id,$list['permission']);
	 				$checked       = (($current_value==1) ? "checked" : "");
		    		$layout .= "
		    			<li>
		    			<label class='checkbox-inline checkbox-success sub_menu'>
		    			<input name='permission[]' value='".$list['permission']."' data-option='".$main_id."'  class='post_permission sub_menu_permission sub_permission_".$main_id."' type='checkbox' $checked>  ".$list['permision_title']."</label>
		    			</li>";
	                $i++;
		    	}
		    }
		    return $layout;
	  	}

	  	// Clean Employee Permission Table

		function cleanEmployeePermissions($user_id)
		{	
			$curr  = date("Y-m-d H:i:s");
			$q     = "SELECT id,permision_title,token,permission FROM ".PERMISSION_DATA_TBL." WHERE 1 AND status='1' ORDER BY id ASC " ;
			$query = $this->exeQuery($q);
			$permissions_array = array();
			if(mysqli_num_rows($query)>0){
		    	while($row = mysqli_fetch_array($query)){
		    		$list = $this->editPagePublish($row);
		    		$permissions_array[] = $list['permission'];
		    	}
		    }
		    $query_p = "UPDATE ".PERMISSION_TBL." SET ";	
			if(count($permissions_array)>0) {
				$i=0;
				foreach ($permissions_array as $key => $value) {
					$query_p.= "". (($i!=0)? ',' : '')."".$value." = '0' ";
					$i++;
				}
			}
			$query_p .= " WHERE user_id='".$user_id."' ";
			$this->exeQuery($query_p);
		    return true;
		}

		// Change User Status

		function changeUserStatus($data)
		{
			$data = $this->decryptData($data);
			$info = $this -> getDetails(USERS_TBL,"status"," id ='$data' ");
			if($info['status'] ==1){
				$query = "UPDATE ".USERS_TBL." SET status='0' WHERE id='$data' ";
			}else{
				$query = "UPDATE ".USERS_TBL." SET status='1' WHERE id='$data' ";
			}
			$up_exe = $this->exeQuery($query);
			if($up_exe){
				return 1;
			}
		}

		// Update the delete status

		function deleteUser($data)
		{
			$data = $this->decryptData($data);
			$info = $this -> getDetails(USERS_TBL,"id,delete_status,is_draft"," id ='$data' AND is_draft='1' ");
			$delete_permission = $this -> deleteRow(PERMISSION_TBL," user_id ='$data'");
			$delete 		   = $this -> deleteRow(USERS_TBL," id ='$data' AND is_draft='1' ");
			return $delete;
		}

		// Trash User

		function trashUser($data)
		{
			$data   = $this->decryptData($data);
			$query  = "UPDATE ".USERS_TBL." SET delete_status='1' WHERE id='$data' ";
			$up_exe = $this->exeQuery($query);
			if($up_exe) {
				return 1;
			}
		}

		// Publish User

		function publishUser($data)
		{
			$data   = $this->decryptData($data);
			$query  = "UPDATE ".USERS_TBL." SET is_draft='0' WHERE id='$data' ";
			$up_exe = $this->exeQuery($query);
			if($up_exe) {
				return 1;
			}	
		}

		// Restore User

		function restoreUser($data)
		{	
			$data   = $this->decryptData($data);
			$info   = $this -> getDetails(USERS_TBL,"delete_status,is_draft"," id ='$data' ");
			$query  = "UPDATE ".USERS_TBL." SET delete_status='0' WHERE id='$data' ";
			$up_exe = $this->exeQuery($query);
			if($up_exe) {
				return 1;
			}
		}


		function inviteEmail($data="")
		{	

			$data 				= $this->decryptData($data);
			$info   			= $this->getDetails(USERS_TBL,"name,email,normal_pass,mobile","id='$data' ");
			$contact_info       = $this->getDetails(COMPANNY_INFO,"*"," id='1' ");
			$curr   			= date("Y-m-d H:i:s");
			$sender_mail 		= NO_REPLY;
			$sender 			= $contact_info['company_name'];
	        $receiver_mail 		= $info['email'];
	        $subject        	= "Login details for"." - ".ucwords($info['name']);
	        $message 			= $this->employeeLoginInfo($info['name'],$info['email'],$info['normal_pass']);
	        $send_mail 			= $this->send_mail($sender_mail,$receiver_mail,$subject,$message);
			if($send_mail){
	            $query  = "UPDATE ".USERS_TBL." SET
					invite_status 	= '1',
					updated_at 		= '$curr'
					WHERE id 		= '$data' ";
				$exe 	= $this->exeQuery($query);
				return 1;
			}else{
				return "Sorry!! Unexpected Error Occurred. Please try again.";
			}	
		}


		// Get State list

		function getStatelist($current="")
	  	{
	  		$layout = "";
	  		$q 		= "SELECT * FROM ".STATE_TBL." WHERE status='1' AND delete_status='0' " ;
	  		$query = $this->exeQuery($q);
		    if(@mysqli_num_rows($query)>0){
		    	$i=0;
		    	while($list = mysqli_fetch_array($query)){
					$selected = (($list['id']==$current) ? 'selected' : '');
					$layout.= "<option value='".$list['id']."' $selected>".$list['name']."</option>";
	                $i++;
		    	}
		    }
		    return $layout;
	  	}


	  	// Get City list

		function getCitylist($current="")
	  	{
	  		$layout = "";
	  		$q 		= "SELECT * FROM ".LOCATIONGROUP_TBL." WHERE status='1' AND delete_status='0' " ;
	  		$query = $this->exeQuery($q);
		    if(@mysqli_num_rows($query)>0){
		    	$i=0;
		    	while($list = mysqli_fetch_array($query)){
					$selected = (($list['id']==$current) ? 'selected' : '');
					$layout.= "<option value='".$list['id']."' $selected>".$list['group_name']."</option>";
	                $i++;
		    	}
		    }
		    return $layout;
	  	}

	  	/*--------------------------------------------- 
					Location City Settings
		----------------------------------------------*/ 	

		function manageLocationCity()
 	  	{
 	  		$layout = "";
	    	$q 	    = "SELECT id,group_name,shipping_cost,status FROM ".LOCATIONGROUP_TBL." WHERE delete_status='0' ORDER BY id ASC" ;
 		    $exe    = $this->exeQuery($q);
 		    if(mysqli_num_rows($exe)>0){
 		    	$i=1;
 		    	while($list = mysqli_fetch_array($exe)){
 		    		$status 	  = (($list['status']==1) ? "On" : "Off"); 
		    		$status_c 	  = (($list['status']==1) ? "checked" : " ");
					$status_class = (($list['status']==1) ? "text-success" : "text-danger");

					$td_data = " data-formclass='edit_LocationCity_class' data-form='editLocationCity' data-option='".$this->encryptData($list['id'])."' ";

		    		$layout .= "
		    				<tr class='nk-tb-item'>
		    					<td class='nk-tb-col openEditLocationCity' $td_data> 
	                                ".$i."
	                            </td>
	                            <td class='nk-tb-col openEditLocationCity' $td_data> 
	                                ".$list['id']."
	                            </td>
	                            <td class='nk-tb-col tb-col-md openEditLocationCity' $td_data> 
	                                <span class='text-primary'>".$this->publishContent($list['group_name'])."</span>
 	                            </td>
 	                            <td class='nk-tb-col tb-col-mb openEditLocationCity' $td_data> 
	                                ".$list['shipping_cost']."
 	                            </td>
 	                            
 	                            <td class='nk-tb-col tb-col-md'>
	                            <div class='custom-control custom-switch'>
                                    <input type='checkbox' class='custom-control-input changeLocationCityStatus' data-option='".$this->encryptData($list['id'])."' value='0' id='status_".$i."' name='save_as_draft' $status_c>
                                    <label class='custom-control-label ' for='status_".$i."'><span class='$status_class'>$status </span></label>
                                </div>
	                        	</td>
 	                            <td class='nk-tb-col nk-tb-col-tools'>
	                                <ul class='nk-tb-actions gx-1'>
	                                    <li class='nk-tb-action-hidden'>
	                                        <button class='btn btn-trigger btn-icon deleteLocationCity' data-formclass='edit_LocationCity_class' data-form='editLocationCity' data-option='".$this->encryptData($list['id'])."' ><em class='icon ni ni-trash-fill'></em></button>
	                                    </li>
	                                </ul>
                            	</td>
	                        </tr>";
		    		$i++;
		    	}
 		    }
 		    return $layout;
 		}

 		// Change Location City Status

		function changeLocationCityStatus($data)
		{
			$data = $this->decryptData($data);
			$info = $this -> getDetails(LOCATIONGROUP_TBL,"status"," id ='$data' ");
			if($info['status'] ==1){
				$query = "UPDATE ".LOCATIONGROUP_TBL." SET status='0' WHERE id='$data' ";
			}else{
				$query = "UPDATE ".LOCATIONGROUP_TBL." SET status='1' WHERE id='$data' ";
			}
			$up_exe = $this->exeQuery($query);
			if($up_exe){
				return 1;
			}
		}

 		// Add Location City

		function addLocationCity($input)
		{
			$data 		   = $this->cleanStringData($input);
			$validate_csrf = $this->validateCSRF($data);
			if ($validate_csrf=="success") {
				$admin_id 		= $_SESSION["ecom_admin_id"];
				$curr 			= date("Y-m-d H:i:s");
				$check = $this->check_query(LOCATIONGROUP_TBL,"*"," token='".$this->hyphenize($data['group_name'])."' OR  group_name LIKE '%".$data['group_name']."%' ");
				if ($check==0) {
					$query = "INSERT INTO ".LOCATIONGROUP_TBL." SET 
							token 			= '".$this->hyphenize($data['group_name'])."',
							group_name 		= '".$data['group_name']."',
							longitude 		= '".$this->cleanString($data['longitude'])."',
							latitude 		= '".$this->cleanString($data['latitude'])."',
							shipping_cost 	= '".$this->cleanString($data['shipping_cost'])."',
							state_id 		= '".$this->cleanString($data['state_id'])."',
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
					$info = $this->getDetails(LOCATIONGROUP_TBL,"id,delete_status"," group_name='".$data['group_name']."' ");
					if ($info['delete_status']==1) {
						return "The entered city already present on the trash. Kindly restore it from the trash section.";
					}else{
						return "The entered city already exists. ";
					}
				}
			}else{
				return $validate_csrf;
			}
		}

		// Get Location City item Details

		function getLocationCityItemDetails($data)
		{		
			$layout 		  = "";
			$locationGroup_id = $this->decryptData($data);
			$query 			  = "SELECT * FROM  ".LOCATIONGROUP_TBL."  WHERE id='$locationGroup_id' ";
	    	$info   		  = $this->getDetails(LOCATIONGROUP_TBL,"*","id='".$locationGroup_id."' ");
			$state_drp 		  = $this->getState($info['state_id']);
			$exe 	          = $this->exeQuery($query);

			if(mysqli_num_rows($exe) > 0) {
				while ($list = mysqli_fetch_assoc($exe)) {
					$layout  = "
					<input type='hidden' name='token' value='".$this->encryptData($list['id'])."' id='token'>
                    <div class='form-group'>
                        <label class='form-label'>City Name
                            <en>*</en>
                        </label>
                        <input type='text' name='group_name' value='".$list['group_name']."' id='group_name'  class='form-control' placeholder='Enter City Name' required>
                    </div>
                     <div class='form-group'>
                        <label class='form-label'>Select State<en>*</en> </label>
                        <div class='form-control-wrap'>
                            <select class='form-select form-control-lg edit_location_group' data-search='on'  name='state_id' id='state_id'  required>
                            ".$state_drp."
                            </select>
                        </div>
                    </div>
                     <div class='form-group'>
                        <label class='form-label'>Longitude
                            <en>*</en>
                        </label>
                        <input type='text' name='longitude' value='".$list['longitude']."' id='edit_longitude'  class='form-control' placeholder='Enter Longitude' required>
                    </div>
                     <div class='form-group'>
                        <label class='form-label'>Latitude
                            <en>*</en>
                        </label>
                        <input type='text' name='latitude' value='".$list['latitude']."' id='edit_latitude'  class='form-control' placeholder='Enter Latitude' required>
                    </div>
                    <div class='form-group'>
                        <label class='form-label'>Shipping Cost <en>*</en> </label>
                        <input type='number' name='shipping_cost' value='".$list['shipping_cost']."' id='shipping_cost' class='form-control' placeholder='Enter Shipping Cost' required>
                    </div>
                    <div class='form-error'>
                    </div>
                    <div class='form-group'>
                        <p class='float-right model_pt'>
                            <button type='button' class='btn btn-light close_modal' data-modal_id='editLocationCityModal'>Cancel</button>
                            <button type='submit' class='btn btn-primary'>Submit</button>
                        </p>
                    </div>";
				}
			}

			return $layout;
		}

		// Edit Location City

		function editLocationCity($data)
		{	
			if(isset($_SESSION[$data['csrf_key']])){
				if($this->cleanString($data['csrf_token']) == $_SESSION[$data['csrf_key']]){
					$admin_id 				= $_SESSION["ecom_admin_id"];
					$locationGroup_id 		= $this->decryptData($data["token"]);
					$curr 					= date("Y-m-d H:i:s");

					$check = $this->check_query(LOCATIONGROUP_TBL,"*","( token='".$this->hyphenize($data['group_name'])."' OR  group_name LIKE '%".$data['group_name']."%') AND id!='".$locationGroup_id."' ");

					if ($check==0) {
						$query  = "UPDATE ".LOCATIONGROUP_TBL." SET 
								token 			= '".$this->hyphenize($data['group_name'])."',
								group_name 		= '".$this->cleanString($data['group_name'])."',
								longitude 		= '".$this->cleanString($data['longitude'])."',
								latitude 		= '".$this->cleanString($data['latitude'])."',
								shipping_cost 	= '".$this->cleanString($data['shipping_cost'])."',
								state_id 		= '".$this->cleanString($data['state_id'])."',
								updated_at 		= '$curr' WHERE id='$locationGroup_id' ";
						$exe 	= $this->exeQuery($query);

						if($exe){
							$this->unSetCSRF($data['csrf_key']);
							return 1;
						}else{
							return "Sorry!! Unexpected Error Occurred. Please try again.";
						}
						
					}else{
						$info = $this->check_query(LOCATIONGROUP_TBL,"*"," group_name='".$data['group_name']."' AND delete_status='1' AND id!='".$locationGroup_id."' ");

						if ($info) {
							return "The entered city already present on the trash. Kindly restore it from the trash section.";
						}else{
							return "The entered city already exists. ";
						}
					}
						
				}else{
					return "Sorry Invalid User Entry. Try Re login to the Panel to continue.";
				}
			}else{
				return "Sorry Invalid User Entry. Try Re login to the Panel to continue.";
			}
		}

		// Update the delete Location City status

		function deleteLocationCity($data)
		{
			$data = $this->decryptData($data);
			$info = $this -> getDetails(LOCATIONGROUP_TBL,"delete_status"," id ='$data' ");
			if($info['delete_status'] ==1){
				$query = "UPDATE ".LOCATIONGROUP_TBL." SET delete_status='0' WHERE id='$data' ";
			}else{
				$query = "UPDATE ".LOCATIONGROUP_TBL." SET delete_status='1' WHERE id='$data' ";
			}
			$up_exe = $this->exeQuery($query);
			if($up_exe){
				return 1;
			}
		}

		// Restore Location City

		function restoreLocationCity($data)
		{	
			$data   = $this->decryptData($data);
			$query  = "UPDATE ".LOCATIONGROUP_TBL." SET delete_status='0' WHERE id='$data' ";
			$up_exe = $this->exeQuery($query);
			if($up_exe) {
				return 1;
			}
		}

		/*--------------------------------------------- 
					Location Group Settings
		----------------------------------------------*/ 	

		function manageLocationGroup()
 	  	{
 	  		$layout = "";
	    	$q 	    = "SELECT LG.id,LG.group_name,LG.shipping_cost,LG.status,LG.city_id,C.group_name as city FROM ".GROUP_TBL." LG LEFT JOIN ".LOCATIONGROUP_TBL." C ON (C.id=LG.city_id) WHERE LG.delete_status='0' ORDER BY LG.id ASC" ;
 		    $exe    = $this->exeQuery($q);
 		    if(mysqli_num_rows($exe)>0){
 		    	$i=1;
 		    	while($list = mysqli_fetch_array($exe)){
 		    		$status 	  = (($list['status']==1) ? "On" : "Off"); 
		    		$status_c 	  = (($list['status']==1) ? "checked" : " ");
					$status_class = (($list['status']==1) ? "text-success" : "text-danger");

					$td_data 	  = "data-formclass='edit_LocationGroup_class' data-form='editLocationGroup' data-option='".$this->encryptData($list['id'])."'";

		    		$layout .= "
		    				<tr class='nk-tb-item'>
		    					<td class='nk-tb-col openEditLocationGroup' $td_data>
	                                ".$i."
	                            </td>
	                            <td class='nk-tb-col openEditLocationGroup' $td_data>
	                                ".$list['id']."
	                            </td>
	                            <td class='nk-tb-col tb-col-md openEditLocationGroup' $td_data>
	                                <span class='text-primary'>".$this->publishContent($list['group_name'])."</span>
 	                            </td>
 	                            <td class='nk-tb-col tb-col-mb openEditLocationGroup' $td_data>
	                                ".$list['city']."
 	                            </td>
 	                            <td class='nk-tb-col tb-col-mb openEditLocationGroup' $td_data>
	                                ".$list['shipping_cost']."
 	                            </td>
 	                            <td class='nk-tb-col tb-col-md'>
	                            <div class='custom-control custom-switch'>
                                    <input type='checkbox' class='custom-control-input changeLocationGroupStatus' data-option='".$this->encryptData($list['id'])."' value='0' id='status_".$i."' name='save_as_draft' $status_c>
                                    <label class='custom-control-label ' for='status_".$i."'><span class='$status_class'>$status </span></label>
                                </div>
	                        	</td>
 	                            <td class='nk-tb-col nk-tb-col-tools'>
	                                <ul class='nk-tb-actions gx-1'>
	                                    <li class='nk-tb-action-hidden'>
	                                        <button class='btn btn-trigger btn-icon deleteLocationGroup'  ><em class='icon ni ni-trash-fill'></em></button>
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

		function changeLocationGroupStatus($data)
		{
			$data = $this->decryptData($data);
			$info = $this -> getDetails(GROUP_TBL,"status"," id ='$data' ");
			if($info['status'] ==1){
				$query = "UPDATE ".GROUP_TBL." SET status='0' WHERE id='$data' ";
			}else{
				$query = "UPDATE ".GROUP_TBL." SET status='1' WHERE id='$data' ";
			}
			$up_exe = $this->exeQuery($query);
			if($up_exe){
				return 1;
			}
		}

 		// Add Location Group

		function addLocationGroup($input)
		{	
			// var_dump($input);
			// return fasle;
			$data 		   = $this->cleanStringData($input);
			$validate_csrf = $this->validateCSRF($data);
			if ($validate_csrf=="success") {
				$admin_id 		= $_SESSION["ecom_admin_id"];
				$curr 			= date("Y-m-d H:i:s");
				$query          = "INSERT INTO ".GROUP_TBL." SET 
							token 			= '".$this->hyphenize($data['group_name'])."',
							group_name 		= '".$data['group_name']."',
							longitude 		= '".$this->cleanString($data['longitude'])."',
							latitude 		= '".$this->cleanString($data['latitude'])."',
							shipping_cost 	= '".$this->cleanString($data['shipping_cost'])."',
							city_id 		= '".$this->cleanString($data['city_id'])."',
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

		function getLocationGroupItemDetails($data)
		{
			$layout      	   = "";
			$locationGroup_id  = $this->decryptData($data);
			$query 			   = "SELECT * FROM  ".GROUP_TBL."  WHERE id='$locationGroup_id' ";
			$exe 	           = $this->exeQuery($query);
			$info 			   = $this->getDetails(GROUP_TBL,"city_id","id='".$locationGroup_id."'");
			$location_city_drp = $this->getCitylist($info['city_id']);
			if(mysqli_num_rows($exe) > 0) {
				while ($list = mysqli_fetch_assoc($exe)) {
					$get_locations = $this->getLocationGroupMappedLocations($list['id']);
					$layout .= "<input type='hidden' name='token' value='".$this->encryptData($list['id'])."' value id='token'>
	                            <div class='form-group'>
	                                <label class='form-label'>Group Name
	                                    <en>*</en>
	                                </label>
	                                <input type='text' name='group_name' value='".$list['group_name']."' id='group_name'  class='form-control' placeholder='Enter Group Name' required>
	                            </div>
	                             <div class='form-group'>
	                                <label class='form-label'>Select City<en>*</en> </label>
	                                <div class='form-control-wrap'>
	                                    <select class='form-select form-control-lg edit_location_city' data-search='on'  name='city_id' id='city_id'  required>
	                                    ".$location_city_drp."
	                                    </select>
	                                </div>
	                            </div>
	                                <input type='hidden' name='longitude' id='edit_longitude'  class='form-control' placeholder='Enter Longitude' required>
	                                <input type='hidden' name='latitude' id='edit_latitude'  class='form-control' placeholder='Enter Latitude' required>
	                            <div class='form-group'>
	                                <label class='form-label'>Shipping Cost <en>*</en> </label>
	                                <input type='number' name='shipping_cost' id='shipping_cost'  value='".$list['shipping_cost']."' class='form-control' placeholder='Enter Shipping Cost' required>
	                            </div>
	                             <div class='form-error'>
	                            </div>
	                            <div class='form-group'>
	                                <p class='float-right model_pt'>
	                                    <button type='button' class='btn btn-light close_modal' data-modal_id='editLocationGroupModal'>Cancel</button>
	                                    <button type='submit' class='btn btn-primary'>Submit</button>
	                                </p>
	                            </div>
	                            "; 
				}
			}

			$location_layouts          = "  <div>
					                            <span class='form-label'> Selected Location : </span>
					                        	<div>".$get_locations."</div>
					                        </div>
					                     ";

			$result                    = array();
			$result['layout']          = $layout;
			$result['mapped_location'] = $location_layouts;

			return json_encode($result);
		}

		function getLocationGroupMappedLocations($location_group)
		{
			$layout = "";
			$query  = "SELECT * FROM ".LOCATION_TBL." WHERE group_id='".$location_group."' AND status='1' AND delete_status='0' ";
			$exe    = $this->exeQuery($query);
			$count  = mysqli_num_rows($exe);
			$i      = 1;
			if(mysqli_num_rows($exe)) {
				while ($details = mysqli_fetch_assoc($exe)) {
					$list       = $this->cleanStringData($details);
					$dot_comma  = (($i==$count)? ". " : ", " );
					$layout    .= $list['location'].$dot_comma;
					$i++;
				}
			}
			return $layout;

		}

		// Edit Location Group

		function editLocationGroup($data)
		{
			if(isset($_SESSION[$data['csrf_key']])){
				if($this->cleanString($data['csrf_token']) == $_SESSION[$data['csrf_key']]){
					$admin_id 				= $_SESSION["ecom_admin_id"];
					$locationGroup_id 		= $this->decryptData($data["token"]);
					$curr 					= date("Y-m-d H:i:s");
					$query  = "UPDATE ".GROUP_TBL." SET 
								token 			= '".$this->hyphenize($data['group_name'])."',
								group_name 		= '".$this->cleanString($data['group_name'])."',
								longitude 		= '".$this->cleanString($data['longitude'])."',
								latitude 		= '".$this->cleanString($data['latitude'])."',
								shipping_cost 	= '".$this->cleanString($data['shipping_cost'])."',
								city_id 		= '".$this->cleanString($data['city_id'])."',
								updated_at 		= '$curr' WHERE id='$locationGroup_id' ";
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

		function deleteLocationGroup($data)
		{
			$data = $this->decryptData($data);
			$info = $this -> getDetails(GROUP_TBL,"delete_status"," id ='$data' ");
			if($info['delete_status'] ==1){
				$query = "UPDATE ".GROUP_TBL." SET delete_status='0' WHERE id='$data' ";
			}else{
				$query = "UPDATE ".GROUP_TBL." SET delete_status='1' WHERE id='$data' ";
			}
			$up_exe = $this->exeQuery($query);
			if($up_exe){
				return 1;
			}
		}

		// Restore Location Group

		function restoreLocationGroup($data)
		{	
			$data   = $this->decryptData($data);
			$query  = "UPDATE ".GROUP_TBL." SET delete_status='0' WHERE id='$data' ";
			$up_exe = $this->exeQuery($query);
			if($up_exe) {
				return 1;
			}
		}
		
		/*--------------------------------------------- 
					Location Settings
		----------------------------------------------*/ 	

		function manageLocation()
 	  	{
 	  		$layout = "";
	    	$q      = "SELECT L.id,L.group_id,L.pincode,L.location,L.status,G.group_name as groupname FROM ".LOCATION_TBL." L LEFT JOIN ".GROUP_TBL." G ON (G.id=L.group_id) WHERE L.delete_status='0' ORDER BY L.id ASC" ;
 		    $exe = $this->exeQuery($q);
 		    if(mysqli_num_rows($exe)>0){
 		    	$i=1;
 		    	while($list = mysqli_fetch_array($exe)){
 		    		$status       = (($list['status']==1) ? "On" : "Off"); 
		    		$status_c     = (($list['status']==1) ? "checked" : " ");
					$status_class = (($list['status']==1) ? "text-success" : "text-danger");
					$td_data 	  = "data-formclass='edit_Location_class' data-form='editLocation' data-option='".$this->encryptData($list['id'])."'";
		    		$layout .= "
		    				<tr class='nk-tb-item'>
		    					<td class='nk-tb-col openEditLocation' $td_data>
	                                ".$i."
	                            </td>
	                            <td class='nk-tb-col openEditLocation' $td_data>
	                                ".$list['id']."
	                            </td>
	                            <td class='nk-tb-col tb-col-md openEditLocation' $td_data>
	                                <span class='text-primary'>".$this->publishContent($list['location'])."</span>
 	                            </td>
 	                            <td class='nk-tb-col tb-col-mb openEditLocation' $td_data>
	                                ".$list['pincode']."
 	                            </td>
 	                            <td class='nk-tb-col tb-col-mb openEditLocation' $td_data>
	                                ".$list['groupname']."
 	                            </td>
 	                            <td class='nk-tb-col tb-col-md'>
	                            <div class='custom-control custom-switch'>
                                    <input type='checkbox' class='custom-control-input changeLocationStatus' data-option='".$this->encryptData($list['id'])."' value='0' id='status_".$i."' name='save_as_draft' $status_c>
                                    <label class='custom-control-label ' for='status_".$i."'><span class='$status_class'>$status </span></label>
                                </div>
	                        	</td>
 	                            <td class='nk-tb-col nk-tb-col-tools'>
	                                <ul class='nk-tb-actions gx-1'>
	                                    <li class='nk-tb-action-hidden'>
	                                        <button class='btn btn-trigger btn-icon deleteLocation' data-formclass='edit_Location_class' data-form='editLocation' data-option='".$this->encryptData($list['id'])."' ><em class='icon ni ni-trash-fill'></em></button>
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

		function changeLocationStatus($data)
		{
			$data = $this->decryptData($data);
			$info = $this -> getDetails(LOCATION_TBL,"status"," id ='$data' ");
			if($info['status'] ==1){
				$query = "UPDATE ".LOCATION_TBL." SET status='0' WHERE id='$data' ";
			}else{
				$query = "UPDATE ".LOCATION_TBL." SET status='1' WHERE id='$data' ";
			}
			$up_exe = $this->exeQuery($query);
			if($up_exe){
				return 1;
			}
		}


 		// Add Location

		function addLocation($input)
		{
			$data 		   = $this->cleanStringData($input);
			$validate_csrf = $this->validateCSRF($data);
			if ($validate_csrf=="success") {
				$admin_id 		= $_SESSION["ecom_admin_id"];
				$curr 			= date("Y-m-d H:i:s");

				$check_location = $this->check_query(LOCATION_TBL,"id","token='".$this->hyphenize($data['location'])."' AND group_id='".$data['group_id']."' ");

				$check_pincode = $this->check_query(LOCATION_TBL,"id"," pincode='".$data['pincode']."' ");

				if($check_location==0) {
					if($check_pincode==0) {
						$query = "INSERT INTO ".LOCATION_TBL." SET 
								token 				= '".$this->hyphenize($data['location'])."',
								location 			= '".$data['location']."',
								longitude 			= '".$this->cleanString($data['longitude'])."',
								latitude 			= '".$this->cleanString($data['latitude'])."',
								pincode 			= '".$this->cleanString($data['pincode'])."',
								group_id 			= '".$this->cleanString($data['group_id'])."',
								status				= '1',
								added_by			= '$admin_id',	
								created_at 			= '$curr',
								updated_at 			= '$curr' ";
						$exe 	= $this->exeQuery($query);
						if($exe){
							$this->unSetCSRF($data['csrf_key']);
							return 1;
						} else {
							return "Sorry!! Unexpected Error Occurred. Please try again.";
						}	
					} else {
						return "Entered Pincode already exists";
					}
				} else {
					return "Entered Location already present in same location group";
				}	
				
			}else{
				return $validate_csrf;
			}
		}

		// Get Location item Details

		function getLocationItemDetails($data)
		{
			$layout 	 = "";
			$location_id = $this->decryptData($data);
			$query 		 = "SELECT L.id,L.token,L.location,L.pincode,L.longitude,L.latitude,L.group_id,L.status,L.delete_status,LG.id as city_id,LG.group_name as city,G.group_name FROM  ".LOCATION_TBL." L LEFT JOIN ".GROUP_TBL." G ON (G.id=L.group_id) LEFT JOIN ".LOCATIONGROUP_TBL." LG ON (LG.id=G.city_id)
				WHERE L.id='$location_id' AND L.status='1' AND L.delete_status='0' ";
			$exe 		 = $this->exeQuery($query);

			if (mysqli_num_rows($exe) > 0) {
				while ($list = mysqli_fetch_assoc($exe)) {
					$layout .= "<input type='hidden' name='token' value='".$this->encryptData($list['id'])."' id='token'>
				                <div class='form-group'>
				                    <label class='form-label'>Location
				                        <en>*</en>
				                    </label>
				                    <input type='text' name='location' id='location'  value='".$list['location']."' class='form-control' placeholder='Enter Location' required>
				                </div>
				                <div class='form-group'>
				                    <label class='form-label'>City <en>*</en> </label>
				                    <div class='form-control-wrap'>
				                        <select class='form-control form-control-lg select_city edit_city' data-for='edit' data-search='on' id='edit_city'  name='city_id' required>
				                            ".$this->getCitylist($list['city_id'])."
				                        </select>
				                    </div>
				                </div>
				                <div class='form-group'>
				                    <label class='form-label'>Location Group <en>*</en> </label>
				                    <div class='form-control-wrap'>
				                        <select class='form-control form-control-lg edit_locationgroup' data-search='on' id='group_id' name='group_id' required>
				                            ".$this->locationgropList($list['city_id'],$list['group_id'])."
				                        </select>
				                    </div>
				                </div>
				                <div class='form-group'>
				                    <label class='form-label'>Pincode
				                        <en>*</en>
				                    </label>
				                    <input type='text' name='pincode' id='edit_pincode' value='".$list['pincode']."'  class='form-control' placeholder='Enter Pincode' required>
				                </div>
				                <div class='form-group'>
				                    <label class='form-label'>Longitude
				                        <en>*</en>
				                    </label>
				                    <input type='text' name='longitude' id='edit_longitude' value='".$list['longitude']."'  class='form-control' placeholder='Enter Longitude' required>
				                </div>
				                <div class='form-group'>
				                    <label class='form-label'>Latitude
				                        <en>*</en>
				                    </label>
				                    <input type='text' name='latitude' id='edit_latitude' value='".$list['latitude']."'  class='form-control' placeholder='Enter Latitude' required>
				                </div>
				                <div class='form-error'>
				                </div>
				                <div class='form-group'>
				                    <p class='float-right model_pt'>
				                        <button type='button' class='btn btn-light close_modal' data-modal_id='editLocationModal'>Cancel</button>
				                        <button type='submit' class='btn btn-primary'>Submit</button>
				                    </p>
				                </div>";
				}
			}


			return $layout; 
		}

		// Get Location Group based on selected city

		function locationgropList($city_id,$current="")
	  	{	
	  		$layout = "";
	  		$q 		= "SELECT * FROM ".GROUP_TBL." WHERE status='1' AND city_id=".$city_id." AND delete_status='0' " ;
	  		$query = $this->exeQuery($q);
		    if(@mysqli_num_rows($query)>0){
		    	$i=0;
		    	while($list = mysqli_fetch_array($query)){
					$selected = (($list['id']==$current) ? 'selected' : '');
					$layout.= "<option value='".$list['id']."' $selected>".$list['group_name']."</option>";
	                $i++;
		    	}
		    }
		    return $layout;
	  	}

		// Edit Location

		function editLocation($data)
		{
			if(isset($_SESSION[$data['csrf_key']])){
				if($this->cleanString($data['csrf_token']) == $_SESSION[$data['csrf_key']]){
					$admin_id 				= $_SESSION["ecom_admin_id"];
					$location_id 			= $this->decryptData($data["token"]);
					$curr 					= date("Y-m-d H:i:s");
					
					$check_location = $this->check_query(LOCATION_TBL,"id","token='".$this->hyphenize($data['location'])."' AND group_id='".$data['group_id']."' AND id!='".$location_id."' ");
					$check_pincode = $this->check_query(LOCATION_TBL,"id"," pincode='".$data['pincode']."' AND id!='".$location_id."' ");

					if($check_location==0) {
						if($check_pincode==0) {
							$query = "UPDATE ".LOCATION_TBL." SET 
										token 			= '".$this->hyphenize($data['location'])."',
										location 		= '".$this->cleanString($data['location'])."',
										group_id 		= '".$this->cleanString($data['group_id'])."',
										pincode 		= '".$this->cleanString($data['pincode'])."',
										longitude 		= '".$this->cleanString($data['longitude'])."',
										latitude 		= '".$this->cleanString($data['latitude'])."',
										updated_at 		= '$curr' WHERE id='$location_id' ";
							$exe 	= $this->exeQuery($query);
							if($exe){
								$this->unSetCSRF($data['csrf_key']);
								return 1;
							}else{
								return "Sorry!! Unexpected Error Occurred. Please try again.";
							}
						} else {
							return "Entered Pincode already exists.";
						}
					} else {
						return "Entered Location already exists in same location group.";
					}	
				} else {
					return "Sorry Invalid User Entry. Try Re login to the Panel to continue.";
				}
			} else {
				return "Sorry Invalid User Entry. Try Re login to the Panel to continue.";
			}
		}


		// Update the delete Location status

		function deleteLocation($data)
		{
			$data = $this->decryptData($data);
			$info = $this -> getDetails(LOCATION_TBL,"delete_status"," id ='$data' ");
			if($info['delete_status'] ==1){
				$query = "UPDATE ".LOCATION_TBL." SET delete_status='0' WHERE id='$data' ";
			}else{
				$query = "UPDATE ".LOCATION_TBL." SET delete_status='1' WHERE id='$data' ";
			}
			$up_exe = $this->exeQuery($query);
			if($up_exe){
				return 1;
			}
		}


		// Restore Location

		function restoreLocation($data)
		{	
			$data   = $this->decryptData($data);
			$query  = "UPDATE ".LOCATION_TBL." SET delete_status='0' WHERE id='$data' ";
			$up_exe = $this->exeQuery($query);
			if($up_exe) {
				return 1;
			}
		}

		// Get Location Group Name to map 

		function getlocationGroup($current="")
	  	{
	  		$layout = "";
	  		$q      = "SELECT id,token,group_name FROM ".GROUP_TBL." WHERE status='1' AND delete_status='0' " ;
	  		$query = $this->exeQuery($q);
		    if(@mysqli_num_rows($query)>0){
		    	$i=0;
		    	while($list = mysqli_fetch_array($query)){
					$selected = (($list['id']==$current) ? 'selected' : '');
					$layout.= "<option value='".$list['id']."' $selected>".$list['group_name']."</option>";
	                $i++;
		    	}
		    }
		    return $layout;
	  	}

	  	// Company Profile Vendor Charges Get Tax List

		function getPaymentTax($current="")
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

	  	// Company Profile Vendor Charges Get Tax List
	  	
		function getShippingTax($current="")
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

	  	// Company Profile order shipping Tax
	  	
		function getOrderShippingTax($current="")
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

		/*--------------------------------------------- 
					 State Management
		----------------------------------------------*/

		// Manage State

		function manageState() 
		{
			$layout = "";
	    	$q      = "SELECT * FROM ".STATE_TBL." WHERE delete_status='0'  ORDER BY id DESC" ;
 		    $exe    = $this->exeQuery($q);
 		    if(mysqli_num_rows($exe)>0){
 		    	$i=1;
 		    	while($list = mysqli_fetch_array($exe)){
		    		$status       = (($list['status']==1) ? "On" : "Off"); 
		    		$status_c     = (($list['status']==1) ? "checked" : " ");
					$status_class = (($list['status']==1) ? "text-success" : "text-danger");


		    		$layout .= "
	    				<tr class='nk-tb-item'>
	    					<td class='nk-tb-col'>
                                ".$i."
                            </td>
                            <td class='nk-tb-col'>
                                ".$list['id']."
                            </td>
                            <td class='nk-tb-col tb-col-md'>
                                <span class='text-primary'>".$this->publishContent($list['name'])."</span>
	                        </td>    	                            
                            <td class='nk-tb-col tb-col-md'>
                            <div class='custom-control custom-switch'>
                                <input type='checkbox' class='custom-control-input changeStateStatus' data-option='".$this->encryptData($list['id'])."' value='0' id='status_".$i."' name='save_as_draft' $status_c>
                                <label class='custom-control-label ' for='status_".$i."'><span class='$status_class'>$status </span></label>
                            </div>
                        	</td>
                            <td class='nk-tb-col nk-tb-col-tools'>
                                <ul class='nk-tb-actions gx-1'>
                                    <li class='nk-tb-action-hidden'>
                                        <button class='btn btn-trigger btn-icon deleteState' data-formclass='edit_state' data-form='editState' data-option='".$this->encryptData($list['id'])."' ><em class='icon ni ni-trash-fill'></em></button>
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

		// Add State

		function addState($input)
		{
			$validate_csrf = $this->validateCSRF($input);
			if($validate_csrf=='success') {
				$data  = $this->cleanStringData($input);
				$check = $this->check_query(STATE_TBL,"id"," name='".$data['name']."' OR  name LIKE '%".$data['name']."%' " );
				$token = $this->hyphenize($data['name']);

				if ($check==0) {
					$admin_id 		= $_SESSION["ecom_admin_id"];
					$curr 			= date("Y-m-d H:i:s");
					$query = "INSERT INTO ".STATE_TBL." SET 
								token 			= '".$token."', 		
								name 			= '".$data['name']."',
								status			= '1',
								created_at 		= '$curr',
								updated_at 		= '$curr' ";
					$exe 	= $this->exeQuery($query);
					if($exe){
						return 1;
					}else{
						return "Sorry!! Unexpected Error Occurred. Please try again.";
					}	
				}else{
					$info = $this->getDetails(STATE_TBL,"id,delete_status"," name='".$data['name']."' ");
					if ($info['delete_status']==1) {
						return "The entered name already present on the trash. Kindly restore it from the trash section.";
					}else{
						return "The entered name already exists. ";
					}
				}
			}else{
				return $validate_csrf;
			}
		}

		// Get State Details

		function getStateDetails($data)
		{
			$layout = "";
			$id     = $this->decryptData($data);
			$query  = "SELECT * FROM  ".STATE_TBL."  WHERE id='$id' ";
			$exe 	= $this->exeQuery($query);

			if(mysqli_num_rows($exe) > 0) {
				while( $list = mysqli_fetch_assoc($exe)) {
					$layout .= "<input type='hidden' name='token' id='token' value='".$this->encryptData($list['id'])."'>
								<div class='form-group'>
					                <label class='form-label'>State Name
					                    <en>*</en>
					                </label>
					                <input type='text' name='name' id='name'  value='".$list['name']."' class='form-control' placeholder='Enter State Name' required>
					            </div>
					            <div class='form-error'>
					            </div>
					            <div class='form-group'>
					                <p class='float-right model_pt'>
					                    <button type='button' class='btn btn-light close_modal' data-modal_id='editStateModal'>Cancel</button>
					                    <button type='submit' class='btn btn-primary'>Submit</button>
					                </p>
					            </div>";
				}
			}

			return $layout;
		}

		// Edit State

		function editState($input)
		{
			$validate_csrf = $this->validateCSRF($input);
			$id 		   = $this->decryptData($input['token']);

			if($validate_csrf=='success'){
				$data = $this->cleanStringData($input);
				$check = $this->check_query(STATE_TBL,"id"," name='".$data['name']."' AND id!='$id' ");
				$token = $this->hyphenize($data['name']);
				if ($check==0) {
					$admin_id 	= $_SESSION["ecom_admin_id"];
					$curr 		= date("Y-m-d H:i:s");
					$query 		= "UPDATE ".STATE_TBL." SET 
								token 			= '".$token."', 
								name 			= '".$this->cleanString($input['name'])."',
								updated_at 		= '$curr'
								WHERE 		  id='$id' ";
					$exe 		= $this->exeQuery($query);
					if($exe){
						return 1;
					}else{
						return "Sorry!! Unexpected Error Occurred. Please try again.";
					}
				}else{
					$info = $this->getDetails(STATE_TBL,"id,delete_status"," name='".$data['name']."' ");
					if ($info['delete_status']==1) {
						return "The entered state already present on the trash. Kindly restore it from the trash section.";
					}else{
						return "The entered state already exists. ";
					}
				}
					
			}else{
				return $validate_csrf;
			}
		}

		// Change State Status

		function changeStateStatus($data)
		{
			$data = $this->decryptData($data);
			$info = $this -> getDetails(STATE_TBL,"status"," id ='$data' ");
			if($info['status'] ==1){
				$query = "UPDATE ".STATE_TBL." SET status='0' WHERE id='$data' ";
			}else{
				$query = "UPDATE ".STATE_TBL." SET status='1' WHERE id='$data' ";
			}
			$up_exe = $this->exeQuery($query);
			if($up_exe){
				return 1;
			}
		}

		// Update State delete status

		function deleteState($data)
		{
			$data   = $this->decryptData($data);
			$info   = $this -> getDetails(STATE_TBL,"delete_status"," id ='$data' ");
			$query  = "UPDATE ".STATE_TBL." SET delete_status='1' WHERE id='$data' ";
			$up_exe = $this->exeQuery($query);
			if($up_exe){
				return 1;
			}
		}

		// Restore State

		function restoreState($data)
		{	
			$data   = $this->decryptData($data);
			$query  = "UPDATE ".STATE_TBL." SET delete_status='0' WHERE id='$data' ";
			$up_exe = $this->exeQuery($query);
			if($up_exe) {
				return 1;
			}
		}

		/*--------------------------------------------- 
					 City Management
		----------------------------------------------*/

		// Manage City

		function manageCity() 
		{
			$layout = "";
	    	$q      = "SELECT * FROM ".CITY_TBL." WHERE delete_status='0'  ORDER BY id DESC" ;
 		    $exe    = $this->exeQuery($q);
 		    if(mysqli_num_rows($exe)>0){
 		    	$i=1;
 		    	while($list = mysqli_fetch_array($exe)){
		    		$status       = (($list['status']==1) ? "On" : "Off"); 
		    		$status_c     = (($list['status']==1) ? "checked" : " ");
					$status_class = (($list['status']==1) ? "text-success" : "text-danger");


		    		$layout .= "
	    				<tr class='nk-tb-item'>
	    					<td class='nk-tb-col'>
                                ".$i."
                            </td>
                            <td class='nk-tb-col'>
                                ".$list['id']."
                            </td>
                            <td class='nk-tb-col tb-col-md'>
                                <span class='text-primary'>".$this->publishContent($list['city'])."</span>
	                        </td>    	                            
                            <td class='nk-tb-col tb-col-md'>
                            <div class='custom-control custom-switch'>
                                <input type='checkbox' class='custom-control-input changeCityStatus' data-option='".$this->encryptData($list['id'])."' value='0' id='status_".$i."' name='save_as_draft' $status_c>
                                <label class='custom-control-label ' for='status_".$i."'><span class='$status_class'>$status </span></label>
                            </div>
                        	</td>
                            <td class='nk-tb-col nk-tb-col-tools'>
                                <ul class='nk-tb-actions gx-1'>
                                    <li class='nk-tb-action-hidden'>
                                        <button class='btn btn-trigger btn-icon openEditCity' data-option='".$this->encryptData($list['id'])."' ><em class='icon ni ni-pen-fill'></em></button>
                                    </li>
                                    <li class='nk-tb-action-hidden'>
                                        <button class='btn btn-trigger btn-icon deleteCity' data-formclass='edit_city' data-form='editCity' data-option='".$this->encryptData($list['id'])."' ><em class='icon ni ni-trash-fill'></em></button>
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

		// Add City

		function addCity($input)
		{
			$validate_csrf = $this->validateCSRF($input);
			if($validate_csrf=='success') {
				$data  = $this->cleanStringData($input);
				$check = $this->check_query(CITY_TBL,"id"," city='".$data['name']."' OR  city LIKE '%".$data['name']."%' ");
				$token = $this->hyphenize($data['name']);
				if ($check==0) {
					$admin_id 		= $_SESSION["ecom_admin_id"];
					$curr 			= date("Y-m-d H:i:s");
					$query = "INSERT INTO ".CITY_TBL." SET 
								token 			= '".$token."', 		
								city 			= '".$data['name']."',
								state_id 		= '".$input['state']."',
								status			='1',
								created_at 		= '$curr',
								updated_at 		= '$curr' ";
					$exe 	= $this->exeQuery($query);
					if($exe){
						return 1;
					}else{
						return "Sorry!! Unexpected Error Occurred. Please try again.";
					}	
				}else{
					$info = $this->getDetails(CITY_TBL,"id,delete_status"," city='".$data['name']."' ");
					if ($info['delete_status']==1) {
						return "The entered city already present on the trash. Kindly restore it from the trash section.";
					}else{
						return "The entered city already exists. ";
					}
				}
			}else{
				return $validate_csrf;
			}
		}

		// Get City Details

		function getCityDetails($data)
		{
			$layout    = "";
			$id        = $this->decryptData($data);
			$query     = "SELECT * FROM  ".CITY_TBL."  WHERE id='$id' ";
			$info      = $this->getDetails(CITY_TBL,"*","id='".$id."'");
			$state_drp = $this->getState($info['state_id']);
			$exe 	   = $this->exeQuery($query);

			if(mysqli_num_rows($exe) > 0) {
				while ($list = mysqli_fetch_assoc($exe)) {
					$layout .= "<input type='hidden' name='token' id='token' value='".$this->encryptData($list['id'])."'>
					            <div class='form-group'>
					                    <label class='form-label'>City Name
					                        <en>*</en>
					                    </label>
					                    <input type='text' name='name' id='name' value='".$list['city']."'  class='form-control' placeholder='Enter City Name' required>
					                </div>
					                 <div class='form-group'>
					                    <label class='form-label' >State</label>
					                    <div class='form-control-wrap'>
					                        <select class='form-select editState' id='editState'  name='state'  data-search='on'>
					                            ".$state_drp."
					                        </select>
					                    </div>
					                </div>
					                <div class='form-error'>
					                </div>
					                <div class='form-group'>
					                    <p class='float-right model_pt'>
					                        <button type='button' class='btn btn-light close_modal' data-modal_id='editCityModal'>Cancel</button>
					                        <button type='submit' class='btn btn-primary'>Submit</button>
					                    </p>
					                </div>
					            ";
				}
			}
			return $layout;
		}

		// Edit City

		function editCity($input)
		{
			$validate_csrf = $this->validateCSRF($input);
			$id 		   = $this->decryptData($input['token']);

			if($validate_csrf=='success'){
				$data = $this->cleanStringData($input);
				$check = $this->check_query(CITY_TBL,"id"," city='".$data['name']."' AND id!='$id' ");
				$token = $this->hyphenize($data['name']);
				if ($check==0) {
					$admin_id 	= $_SESSION["ecom_admin_id"];
					$curr 		= date("Y-m-d H:i:s");
					$query 		= "UPDATE ".CITY_TBL." SET 
								token 			= '".$token."', 
								city 			= '".$this->cleanString($input['name'])."',
								state_id 		= '".$input['state']."',
								updated_at 		= '$curr'
								WHERE id='$id' ";
					$exe 		= $this->exeQuery($query);
					if($exe){
						return 1;
					}else{
						return "Sorry!! Unexpected Error Occurred. Please try again.";
					}
				}else{
					$info = $this->getDetails(CITY_TBL,"id,delete_status"," city='".$data['name']."' ");
					if ($info['delete_status']==1) {
						return "The entered city already present on the trash. Kindly restore it from the trash section.";
					}else{
						return "The entered city already exists. ";
					}
				}
					
			}else{
				return $validate_csrf;
			}
		}

		// Change City Status

		function changeCityStatus($data)
		{
			$data = $this->decryptData($data);
			$info = $this -> getDetails(CITY_TBL,"status"," id ='$data' ");
			if($info['status'] ==1){
				$query = "UPDATE ".CITY_TBL." SET status='0' WHERE id='$data' ";
			}else{
				$query = "UPDATE ".CITY_TBL." SET status='1' WHERE id='$data' ";
			}
			$up_exe = $this->exeQuery($query);
			if($up_exe){
				return 1;
			}
		}

		// Update City delete status

		function deleteCity($data)
		{
			$data   = $this->decryptData($data);
			$info   = $this -> getDetails(CITY_TBL,"delete_status"," id ='$data' ");
			$query  = "UPDATE ".CITY_TBL." SET delete_status='1' WHERE id='$data' ";
			$up_exe = $this->exeQuery($query);
			if($up_exe){
				return 1;
			}
		}

		// Restore City

		function restoreCity($data)
		{	
			$data   = $this->decryptData($data);
			$query  = "UPDATE ".CITY_TBL." SET delete_status='0' WHERE id='$data' ";
			$up_exe = $this->exeQuery($query);
			if($up_exe) {
				return 1;
			}
		}

		/*--------------------------------------------- 
					 Pincode Management
		----------------------------------------------*/

		// Manage Pincode

		function managePincode() 
		{
			$layout = "";
	    	$q      = "SELECT * FROM ".PINCODE_TBL." WHERE delete_status='0'  ORDER BY id DESC" ;
 		    $exe    = $this->exeQuery($q);
 		    if(mysqli_num_rows($exe)>0){
 		    	$i=1;
 		    	while($list = mysqli_fetch_array($exe)){
		    		$status       = (($list['status']==1) ? "On" : "Off"); 
		    		$status_c     = (($list['status']==1) ? "checked" : " ");
					$status_class = (($list['status']==1) ? "text-success" : "text-danger");


		    		$layout .= "
	    				<tr class='nk-tb-item'>
	    					<td class='nk-tb-col'>
                                ".$i."
                            </td>
                            <td class='nk-tb-col'>
                                ".$list['id']."
                            </td>
                            <td class='nk-tb-col tb-col-md'>
                                <span class='text-primary'>".$this->publishContent($list['pincode'])."</span>
	                        </td>    	                            
                            <td class='nk-tb-col tb-col-md'>
                            <div class='custom-control custom-switch'>
                                <input type='checkbox' class='custom-control-input changePincodeStatus' data-option='".$this->encryptData($list['id'])."' value='0' id='status_".$i."' pincode='save_as_draft' $status_c>
                                <label class='custom-control-label ' for='status_".$i."'><span class='$status_class'>$status </span></label>
                            </div>
                        	</td>
                            <td class='nk-tb-col nk-tb-col-tools'>
                                <ul class='nk-tb-actions gx-1'>
                                    <li class='nk-tb-action-hidden'>
                                        <button class='btn btn-trigger btn-icon deletePincode' data-formclass='edit_city' data-form='editCity' data-option='".$this->encryptData($list['id'])."' ><em class='icon ni ni-trash-fill'></em></button>
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

		// Add Pincode

		function addPincode($input)
		{
			$validate_csrf = $this->validateCSRF($input);
			if($validate_csrf=='success') {
				$data  = $this->cleanStringData($input);
				$check = $this->check_query(PINCODE_TBL,"id"," pincode='".$data['pincode']."' ");

				if ($check==0) {
					$admin_id 		= $_SESSION["ecom_admin_id"];
					$curr 			= date("Y-m-d H:i:s");
					$query = "INSERT INTO ".PINCODE_TBL." SET 
								pincode 		= '".$data['pincode']."',
								city_id 		= '".$input['city']."',
								status			='1',
								created_at 		= '$curr',
								updated_at 		= '$curr' ";
					$exe 	= $this->exeQuery($query);
					if($exe){
						return 1;
					}else{
						return "Sorry!! Unexpected Error Occurred. Please try again.";
					}	
				}else{
					$info = $this->getDetails(PINCODE_TBL,"id,delete_status"," pincode='".$data['pincode']."' ");
					if ($info['delete_status']==1) {
						return "The entered pincode already present on the trash. Kindly restore it from the trash section.";
					}else{
						return "The entered pincode already exists. ";
					}
				}
			}else{
				return $validate_csrf;
			}
		}

		// Change Pincode Status

		function changePincodeStatus($data)
		{
			$data = $this->decryptData($data);
			$info = $this -> getDetails(PINCODE_TBL,"status"," id ='$data' ");
			if($info['status'] ==1){
				$query = "UPDATE ".PINCODE_TBL." SET status='0' WHERE id='$data' ";
			}else{
				$query = "UPDATE ".PINCODE_TBL." SET status='1' WHERE id='$data' ";
			}
			$up_exe = $this->exeQuery($query);
			if($up_exe){
				return 1;
			}
		}

		// Update Pincode delete status

		function deletePincode($data)
		{
			$data   = $this->decryptData($data);
			$info   = $this -> getDetails(PINCODE_TBL,"delete_status"," id ='$data' ");
			$query  = "UPDATE ".PINCODE_TBL." SET delete_status='1' WHERE id='$data' ";
			$up_exe = $this->exeQuery($query);
			if($up_exe){
				return 1;
			}
		}

		// Restore Pincode

		function restorePincode($data)
		{	
			$data   = $this->decryptData($data);
			$query  = "UPDATE ".PINCODE_TBL." SET delete_status='0' WHERE id='$data' ";
			$up_exe = $this->exeQuery($query);
			if($up_exe) {
				return 1;
			}
		}

		/*--------------------------------------------- 
					  Branch Management
		----------------------------------------------*/

		// Manage Branch 

		function manageBranch() 
		{
			$layout = "";
	    	$q 		= "SELECT * FROM ".BRANCH_TBL." WHERE 1 AND delete_status='0' ORDER BY id ASC" ;
 		    $exe 	= $this->exeQuery($q);
 		    if(mysqli_num_rows($exe)>0){
 		    	$i=1;
 		    	while($list = mysqli_fetch_array($exe)){
		    		$status       		= (($list['status']==1) ? "On" : "Off"); 
		    		$status_c     		= (($list['status']==1) ? "checked" : " ");
					$status_class 		= (($list['status']==1) ? "text-success" : "text-danger");
					// Draft Status
					$draft_status 		= (($list['is_draft']==1) ? "Draft" : "Published"); 
					$draft_status_c 	= (($list['is_draft']==1) ? "checked" : ""); 
					$draft_status_class = (($list['is_draft']==1) ? "text-warning" : "text-success"); 
					$is_draft_row 		= (($list['is_draft']==1) ? "draft_item" : ""); 

					if ($list['is_draft']==1) {
						$draft_action = "<button type='button' data-option='".$this->encryptData($list['id'])."' class='btn btn-warning btn-dim btn-sm publishBranch' ><em class='icon ni ni-check-thick'></em> Publish</button>";
						$delete_class 		= 'deleteBranch';
						$delete_class_hover = 'Delete Branch';

					}else{
						$draft_action = "<div class='tb-tnx-status'>
	                            	<span class='badge badge-dot text-success cursor_pointer'> Published </span>
	                            </div>";
	                    $delete_class 		= 'trashBranch';
	                    $delete_class_hover = 'Trash Branch';
	                    
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

					$image = (($list['logo']=='') ? ASSETS_PATH.'file_upload.jpg'  : UPLOADS.$list['logo']);
		    		$layout .= "
	    				<tr class='nk-tb-item $is_draft_row'>
	    					<td class='nk-tb-col'>
                                ".$i."
                            </td>
                            <td class='nk-tb-col'>
                                ".$list['id']."
                            </td>
                            <td class='nk-tb-col'>
                               <img src='".$image."' width='50' class='img-thumbnail'/>
                            </td>
                            <td class='nk-tb-col tb-col-md'>
                                <span class='text-primary'>".$this->publishContent($list['branch_name'])."</span>
	                        </td>
	                        <td class='nk-tb-col tb-col-md'>
	                            <div class='custom-control custom-switch'>
                                    <input type='checkbox' class='custom-control-input changeBranchStatus' data-option='".$this->encryptData($list['id'])."'   value='0'  id='status_".$i."' name='save_as_draft' $status_c>
                                    <label class='custom-control-label ' for='status_".$i."'><span class='$status_class'>$status </span></label>
                                </div>
	                        </td>
	                        <td class='nk-tb-col'>
                               	$draft_action
                            </td>
                            <td class='nk-tb-col nk-tb-col-tools'>
                                <ul class='nk-tb-actions gx-1'>
                                	".$preview."
                                    <li class='nk-tb-action-hidden'>
                                        <a class='btn btn-trigger btn-icon' data-toggle='tooltip' data-placement='top' title='Edit' href='".COREPATH."company/editbranch/".$list['id']."'  ><em class='icon ni ni-pen-fill'></em></a>
                                    </li>
                                    <li class='nk-tb-action-hidden'>
                                        <a href='javascript:void();' class='btn btn-trigger btn-icon $delete_class' data-toggle='tooltip' data-placement='top' title='$delete_class_hover' data-option='".$this->encryptData($list['id'])."' >
                                            <em class='icon ni ni-trash-fill'></em>
                                        </a>
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

		// Get state to map 

		function getState($current="")
	  	{
	  		$layout = "";
	  		$q 		= "SELECT id,name FROM ".STATE_TBL." WHERE status='1' AND delete_status='0' " ;
	  		$query = $this->exeQuery($q);
		    if(@mysqli_num_rows($query)>0){
		    	$i=0;
		    	while($list = mysqli_fetch_array($query)){
					$selected = (($list['id']==$current) ? 'selected' : '');
					$layout.= "<option value='".$list['id']."' $selected>".$list['name']."</option>";
	                $i++;
		    	}
		    }
		    return $layout;
	  	}

	  	// Get state to map 

		function getCity($current="")
	  	{
	  		$layout = "";
	  		$q 		= "SELECT id,city FROM ".CITY_TBL." WHERE status='1' AND delete_status='0' " ;
	  		$query = $this->exeQuery($q);
		    if(@mysqli_num_rows($query)>0){
		    	$i=0;
		    	while($list = mysqli_fetch_array($query)){
					$selected = (($list['id']==$current) ? 'selected' : '');
					$layout.= "<option value='".$list['id']."' $selected>".$list['city']."</option>";
	                $i++;
		    	}
		    }
		    return $layout;
	  	}

		// Add Branch 

		function addBranch($input,$image)
		{
			$validate_csrf = $this->validateCSRF($input);
			if($validate_csrf=='success') {
				$data 		   = $this->cleanStringData($input);
				$admin_id 	   = $_SESSION["ecom_admin_id"];
				$curr 		   = date("Y-m-d H:i:s");
				$save_as_draft = isset($data['save_as_draft']) ? 1 : 0;

				// Add Images

				if ($image['is_uploaded']) {
				$file_status = $image['status'];
				$image_q = "
					 logo 	= '".$image['file_name']."',
				";
				}else{
					$file_status = "ok";
					$image_q = "";
				}
				if ($file_status=="ok") {
				
					$query = "INSERT INTO ".BRANCH_TBL." SET
								token 				= '".$this->hyphenize($data['branch_name'])."', 
								branch_name 		= '".($data['branch_name'])."',
								contact_no_one 		= '".($data['contact_no_one'])."',
								contact_no_two 		= '".($data['contact_no_two'])."',
								email_one 			= '".($data['email_one'])."',
								email_two 			= '".($data['email_two'])."',
								address_one			= '".($data['address_one'])."',
								address_two 		= '".($data['address_two'])."',
								whatsapp_one 		= '".($data['whatsapp_one'])."',
								whatsapp_two 		= '".($data['whatsapp_two'])."',
								facebook     		= '".($data['facebook'])."',
								twitter     		= '".($data['twitter'])."',
								googleplus	 		= '".($data['googleplus'])."',
								rss 	 			= '".($data['rss'])."',
								pinterest 	 		= '".($data['pinterest'])."',
								linkedin 	 		= '".($data['linkedin'])."',
								youtube 	 		= '".($data['youtube'])."',
								instagram 			= '".($data['instagram'])."',
								gst_no 				= '".($data['gst_no'])."',
								registered_state_id = '".($data['reg_state'])."',
								is_draft 			= '".$save_as_draft."',
								".$image_q."
								status 				= '1',
								updated_at 			= '$curr'";
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

		// Edit Branch 

		function editBranch($input,$image)
		{ 
			$validate_csrf = $this->validateCSRF($input);
			if($validate_csrf=='success'){
				$data 			= $this->cleanStringData($input);
				$id 			= $this->decryptData($data['session_token']);
				$admin_id 		= $_SESSION["ecom_admin_id"];
				$curr 			= date("Y-m-d H:i:s");
				$save_as_draft 	= isset($data['save_as_draft']) ? 1 : 0;

					// Add Images

					if ($image['is_uploaded']) {
						$file_status = $image['status'];
						$image_q = "
							 logo 	= '".$image['file_name']."',
							
						";

						// Remove previous image
						$info = $this->getDetails(BRANCH_TBL,"logo"," id='$id' ");
						if ($info['logo']!='') {
							unlink("./resource/uploads/".$info['logo']);
						}
					}else{
						$file_status = "ok";
						$image_q 	 = "";
					}	

					if ($file_status=="ok") {
						 $query = "UPDATE ".BRANCH_TBL." SET 
								branch_name 		= '".($data['branch_name'])."',
								contact_no_one 		= '".($data['contact_no_one'])."',
								contact_no_two 		= '".($data['contact_no_two'])."',
								email_one 			= '".($data['email_one'])."',
								email_two 			= '".($data['email_two'])."',
								address_one			= '".($data['address_one'])."',
								address_two 		= '".($data['address_two'])."',
								whatsapp_one 		= '".($data['whatsapp_one'])."',
								whatsapp_two 		= '".($data['whatsapp_two'])."',
								facebook     		= '".($data['facebook'])."',
								twitter     		= '".($data['twitter'])."',
								googleplus	 		= '".($data['googleplus'])."',
								rss 	 			= '".($data['rss'])."',
								pinterest 	 		= '".($data['pinterest'])."',
								linkedin 	 		= '".($data['linkedin'])."',
								youtube 	 		= '".($data['youtube'])."',
								instagram 			= '".($data['instagram'])."',
								gst_no 				= '".($data['gst_no'])."',
								registered_state_id = '".($data['reg_state'])."',
								is_draft 			= '".$save_as_draft."',
								".$image_q."
								status 				= '1',
								updated_at 			= '$curr' WHERE id='$id' ";
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

		// Change Branch  Status

		function changeBranchStatus($data)
		{
			$data = $this->decryptData($data);
			$info = $this -> getDetails(BRANCH_TBL,"status"," id ='$data' ");
			if($info['status'] ==1){
				$query = "UPDATE ".BRANCH_TBL." SET status='0' WHERE id='$data' ";
			}else{
				$query = "UPDATE ".BRANCH_TBL." SET status='1' WHERE id='$data' ";
			}
			$up_exe = $this->exeQuery($query);
			if($up_exe){
				return 1;
			}
		}

		// Update Branch delete status

		function deleteBranch($data)
		{	
			$data = $this->decryptData($data);
			$info = $this -> getDetails(BRANCH_TBL,"file_name"," id ='$data' AND is_draft='1' ");
			// Remove Image
			if ($info['file_name']!='') {
				unlink("./resource/uploads/".$info['file_name']);
			}
			$delete = $this -> deleteRow(BRANCH_TBL," id ='$data' AND is_draft='1' ");
			return $delete;
		}

		// Trash Branch

		function trashBranch($data)
		{
			$data   = $this->decryptData($data);
			$query  = "UPDATE ".BRANCH_TBL." SET delete_status='1' WHERE id='$data' ";
			$up_exe = $this->exeQuery($query);
			if($up_exe) {
				return 1;
			}
		}

		// Publish Branch

		function publishBranch($data)
		{
			$data   = $this->decryptData($data);
			$query  = "UPDATE ".BRANCH_TBL." SET is_draft='0' WHERE id='$data' ";
			$up_exe = $this->exeQuery($query);
			if($up_exe) {
				return 1;
			}	
		}

		// Restore Branch

		function restoreBranch($data)
		{	
			$data   = $this->decryptData($data);
			$query  = "UPDATE ".BRANCH_TBL." SET delete_status='0' WHERE id='$data' ";
			$up_exe = $this->exeQuery($query);
			if($up_exe) {
				return 1;
			}
		}

		/*--------------------------------------------- 
					 Email Settings Management
		----------------------------------------------*/


		// Edit Email

		function editEmail($input)
		{
			$validate_csrf = $this->validateCSRF($input);
			
			if($validate_csrf=='success'){
					$data 		= $this->cleanStringData($input);
					$admin_id 	= $_SESSION["ecom_admin_id"];
					$curr 		= date("Y-m-d H:i:s");
					$query 		= "UPDATE ".EMAIL_SETTINGS_TBL." SET 
								pro_vendor    		= '".$this->cleanString($input['pro_vendor'])."',
								pro_smtp_server 	= '".$this->cleanString($input['pro_smtp_server'])."',
								pro_smtp_port 		= '".$this->cleanString($input['pro_smtp_port'])."',
								pro_smtp_username   = '".$this->cleanString($input['pro_smtp_username'])."',
								pro_smtp_password   = '".$this->cleanString($input['pro_smtp_password'])."',
								pro_ssl_tls       	= '".$this->cleanString($input['pro_ssl_tls'])."',
								dev_vendor    		= '".$this->cleanString($input['dev_vendor'])."',
								dev_smtp_server 	= '".$this->cleanString($input['dev_smtp_server'])."',
								dev_smtp_port 		= '".$this->cleanString($input['dev_smtp_port'])."',
								dev_smtp_username   = '".$this->cleanString($input['dev_smtp_username'])."',
								dev_smtp_password   = '".$this->cleanString($input['dev_smtp_password'])."',
								dev_ssl_tls       	= '".$this->cleanString($input['dev_ssl_tls'])."',
								updated_at    		= '$curr' WHERE id='1' ";
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

		/*--------------------------------------------- 
					 SMS Settings Management
		----------------------------------------------*/

		// Edit Sms

		function editSms($input)
		{
			$validate_csrf = $this->validateCSRF($input);

			if($validate_csrf=='success'){
					$data 		= $this->cleanStringData($input);
					$admin_id 	= $_SESSION["ecom_admin_id"];
					$curr 		= date("Y-m-d H:i:s");
					$query 		= "UPDATE ".SMS_SETTINGS_TBL." SET 
								username      = '".$this->cleanString($input['username'])."',
								password      = '".$this->cleanString($input['password'])."',
								sender_id     = '".$this->cleanString($input['sender_id'])."',
								updated_at    = '$curr' WHERE id='1' ";
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

		/*--------------------------------------------- 
					 Payment Settings Management
		----------------------------------------------*/

		// Edit Payment

		function editPayment($input)
		{
			$validate_csrf = $this->validateCSRF($input);
			if($validate_csrf=='success'){
					$data 		= $this->cleanStringData($input);
					$admin_id 	= $_SESSION["ecom_admin_id"];
					$curr 		= date("Y-m-d H:i:s");
					$query 		= "UPDATE ".PAYMENT_SETTINGS_TBL." SET
								title  		   = '".$this->cleanString($input['title'])."',
								description    = '".$this->cleanString($input['description'])."',
								updated_at     = '$curr' WHERE id='1' ";
					$exe 		= $this->exeQuery($query);


					$query 		= "UPDATE ".PAYMENT_SETTINGS_TBL." SET
								prod_client_id			= '".$this->cleanString($input['prod_client_id'])."',
								prod_client_secret		= '".$this->cleanString($input['prod_client_secret'])."',
								dev_client_id			= '".$this->cleanString($input['dev_client_id'])."',
								dev_client_secret_key	= '".$this->cleanString($input['dev_client_secret_key'])."',
								updated_at     			= '$curr' WHERE id='2' ";
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

		/*--------------------------------------------- 
					 Site Settings
		----------------------------------------------*/

		// Manage Site Sittings

		function manageSiteSettings() 
		{
			$layout = "";
	    	$q      = "SELECT * FROM ".SITE_SETTINGS_TBL."" ;
 		    $exe    = $this->exeQuery($q);
 		    if(mysqli_num_rows($exe)>0){
 		    	$i=1;
 		    	while($list = mysqli_fetch_array($exe)){
		    		$status       = (($list['status']==1) ? "On" : "Off"); 
		    		$status_c     = (($list['status']==1) ? "checked" : " ");
					$status_class = (($list['status']==1) ? "text-success" : "text-danger");

		    		$layout .= "
	    				<tr class='nk-tb-item'>
	    					<td class='nk-tb-col'>
                                ".$i."
                            </td>
                            <td class='nk-tb-col tb-col-md'>
                                <span class='text-primary'>".$this->publishContent($list['id'])."</span>
	                        </td> 
	                        <td class='nk-tb-col tb-col-md'>
                                <span class='text-primary'>".$this->publishContent(ucwords(str_replace("_", " ", $list['controller_name'])))."</span>
	                        </td>
	                        <td class='nk-tb-col tb-col-md'>
                                <span class='text-primary'>".$this->publishContent($list['parent_id'])."</span>
	                        </td>    	                            
                            <td class='nk-tb-col tb-col-md'>
                            <div class='custom-control custom-switch'>
                                <input type='checkbox' class='custom-control-input changeSiteSettingsStatus' data-option='".$this->encryptData($list['id'])."' value='0' id='status_".$i."' name='save_as_draft' $status_c>
                                <label class='custom-control-label ' for='status_".$i."'><span class='$status_class'>$status </span></label>
                            </div>
                        	</td>
                        </tr>";
		    		$i++;
		    	}
 		    }
 		    return $layout;
		}

		// Change Site Settings Status

		function changeSiteSettingsStatus($data)
		{
			$data = $this->decryptData($data);
			$info = $this -> getDetails(SITE_SETTINGS_TBL,"status"," id ='$data' ");
			if($info['status'] ==1){
				$query = "UPDATE ".SITE_SETTINGS_TBL." SET status='0' WHERE id='$data' ";
			}else{
				$query = "UPDATE ".SITE_SETTINGS_TBL." SET status='1' WHERE id='$data' ";
			}
			$up_exe = $this->exeQuery($query);
			if($up_exe){
				return 1;
			}
		}

		/*----------------------------------------------
						Analytics
		-----------------------------------------------*/

		// Update Analytics 

		function updateAnalytics($input)
		{
			$validate_csrf = $this->validateCSRF($input);
			if ($validate_csrf=="success") {
				$admin_id 		= $_SESSION["ecom_admin_id"];
				$curr 			= date("Y-m-d H:i:s");
				
				$gdpr = 0;
				if(isset($input['gdpr_cookies'])) {
					$gdpr = 1;
				}
				$query 			= "UPDATE ".ANALYTICS_TBL." SET 
							google_analytics_id 	= '".$this->cleanString($input['google_analytics_id'])."',
							global_site_tag 		= '".$this->cleanString($input['global_site_tag'])."',
							event_snippet 			= '".$this->cleanString($input['event_snippet'])."',
							gdpr_cookies 			= '".$gdpr."',
							gdpr_title			    = '".$this->cleanString($input['gdpr_title'])."',
							gdpr_content			= '".$this->cleanString($input['gdpr_content'])."',
							updated_by				= '$admin_id',	
							created_at 				= '$curr',
							updated_at 				= '$curr' WHERE id='1' ";
				$exe = $this->exeQuery($query);
				if($exe){
					$this->unSetCSRF($input['csrf_key']);
					return 1;
				}else{
					return "Sorry!! Unexpected Error Occurred. Please try again.";
				}	
			}else{
				return $validate_csrf;
			}
		}

		/*--------------------------------------------
					Shipping & Delivery
		----------------------------------------------*/
		
		function manageShippingDelivery()
		{
			$layout = "";

	    	$q      = "SELECT * FROM ".STATE_TBL." WHERE 1  ORDER BY name  ASC" ;
 		    $exe    = $this->exeQuery($q);
 		    if(mysqli_num_rows($exe)>0){
 		    	$i=1;
 		    	while($list = mysqli_fetch_array($exe)){
 		    		$is_active = (($list['active_delivery']==1) ? "checked" : "");
		    		$layout .= "
	    				 <div id='accordion".$i."' class='accordion'>
                            <div class='accordion-item'>
                            	<div class='row'>
                            		<div class='custom-control custom-control-sm custom-switch manage_shipping_state_checkbox' >
									    <input type='checkbox' class='custom-control-input toggle_state' name='active_state[]' data-option='".$list['id']."' id='stateSwitch_".$list['id']."' value='".$list['id']."' $is_active>
									    <label class='custom-control-label' for='stateSwitch_".$list['id']."'></label>
									</div>

                               
                                </div>
                                 <a href='#' class='accordion-head stateAccordionHead collapsed state_accordion_list' data-toggle='collapse' data-option='".$list['id']."' data-target='#accordion".$i."-item-1' >
                                
                                    <h6 class='title'> ".$list["name"]."</h6>
                                    <span class='accordion-icon'></span>
                                </a>

                                <div class='accordion-body collapse' id='accordion".$i."-item-1' data-parent='#accordion".$i."'>
                                    <div class='accordion-inner padding_none stateAccordion_".$list['id']." '>";
                                    $layout .= " ".$this->getCityAccordion($list['id'])." ";
                                  	$layout .= "  </div>
                                </div>
                            </div>
                        </div>";
		    		$i++;
		    	}
 		    }
 		    return $layout;
		}

		function getCityAccordion ($state_id)
		{
			$layout = "";

	    	$q      = "SELECT * FROM ".CITY_TBL." WHERE state_id=".$state_id."  ORDER BY city  ASC" ;
 		    $exe    = $this->exeQuery($q);
 		    if(mysqli_num_rows($exe)>0){
 		    	$i=1;
 		    	while($list = mysqli_fetch_array($exe)){
 		    		$is_active = (($list['active_delivery']==1) ? "checked" : "");
		    		$layout .= "
							<div id='accordion_C_".$list['id']."' class='accordion border_none' >
								<div class='accordion-item' >
								<div class='row'>
                            		<div class='custom-control custom-control-sm custom-switch city_accordion_list' >
									    <input type='checkbox' class='custom-control-input toggle_city  select_all city_toggle_".$list['state_id']."' data-option='".$list['id']."' name='active_city[]' value='".$list['id']."' id='citySwitch_".$list['id']."' $is_active>
									    <label class='custom-control-label' for='citySwitch_".$list['id']."'></label>
									</div>

                               
                                </div>
									<a href='#' class='accordion-head openEditPincode state_accordion_list' id='City".$list['id']."' data-formclass='pincode_delivery_status'  data-form='addBlogCategory' data-option='".$this->encryptData($list['id'])."'>
									<h6 class='title inner_block' >".$list['city']."</h6>
									</a>
									<div class='accordion-body collapse' id='accordion_C_".$list['id']."-item-1' data-parent='#accordion_C_".$list['id']."'>
										 <div class='accordion-inner  pincodeAccordion_".$list['id']." '>";
		                                  	$layout .= "  </div>
									</div>
								</div>
							</div>  
	                       ";
		    		$i++;
		    	}
 		    }
 		    return $layout;
		}

		function getPincodeAccordion ($city_id)
		{
			$layout = "";

	    	$q      = "SELECT * FROM ".PINCODE_TBL." WHERE city_id=".$city_id."  ORDER BY pincode  ASC" ;
 		    $exe    = $this->exeQuery($q);
 		    if(mysqli_num_rows($exe)>0){
 		    	$i=1;
 		    	while($list = mysqli_fetch_array($exe)){
 		    		$is_active = (($list['active_delivery']==1) ? "checked" : "");
                    $layout .="
                            <div class='custom-control custom-control-sm custom-checkbox inner_block2'>
                                <input type='checkbox' class='custom-control-input pincode_group_".$city_id."' name='active_pincode[]' value='".$list['id']."' id='pincode_".$list['id']."' $is_active>
                            	<label class='custom-control-label' for='pincode_".$list['id']."'><h6>".$list['pincode']."</h6></label>
                            </div>
	                       ";
		    		$i++;
		    	}
 		    } else {
 		    	$layout .= "No Pincodes Available Hear.";
 		    }
 		    return $layout;
		}

		// Get Pincode Details Details

		function getPincodeInfo($data)
		{
			$id = $this->decryptData($data);
			$query  = "SELECT * FROM ".PINCODE_TBL." WHERE city_id=".$id."  ORDER BY pincode  ASC" ;
			$exe 	= $this->exeQuery($query);
			$info   = $this->editPagePublish(mysqli_fetch_assoc($exe));

			$city_id = $id;

			$layout = "";

	    	$q      = "SELECT * FROM ".PINCODE_TBL." WHERE city_id=".$city_id."  ORDER BY pincode  ASC" ;
 		    $exe    = $this->exeQuery($q);
 		    if(mysqli_num_rows($exe)>0){
 		    	$i=1;
 		    	while($list = mysqli_fetch_array($exe)){
 		    		$is_active = (($list['active_delivery']==1) ? "checked" : "");
                    $layout .="
                            <div class='custom-control custom-control-sm custom-checkbox inner_block2'>
                                <input type='checkbox' class='custom-control-input pincode_group_".$city_id."' name='active_pincode[]' value='".$list['id']."' id='pincode_".$list['id']."' $is_active>
                            	<label class='custom-control-label' for='pincode_".$list['id']."'><h6>".$list['pincode']."</h6></label>
                            </div>
	                       ";
		    		$i++;
		    	}
 		    } else {
 		    	$layout .= "No Pincodes Available Hear.";
 		    }

 		    $result = array();
 		    $result['info'] = $info;
 		    $result['html'] = $layout;

 		    return $result;
		}

		function updatePincode($input)
		{	
			$validate_csrf = $this->validateCSRF($input);
			if($validate_csrf=='success'){
					$admin_id 	= $_SESSION["ecom_admin_id"];
					$curr 		= date("Y-m-d H:i:s");

					$inactive_status = $this->inactiveDeliveryStatus();
					
					if(isset($input['active_state'])) {
						$active_state = $input['active_state'];
					} else {
						$active_state = [];
					}

					if(isset($input['active_city'])) {
						$active_city = $input['active_city'];
					} else {
						$active_city = [];
					}

					if(isset($input['active_pincode'])) {
						$active_pincode = $input['active_pincode'];
					} else {
						$active_pincode = [];
					}

					$exe = 1;

					if(count($active_state)>0) {
						$query = 	"UPDATE ".STATE_TBL." SET active_delivery='1' WHERE id IN (" . implode(',', array_map('intval',$input['active_state'])) . ")";
						$exe 		= $this->exeQuery($query);
					}

					if(count($active_city)>0 && $exe) {
						$query = 	"UPDATE ".CITY_TBL." SET active_delivery='1' WHERE id IN (" . implode(',', array_map('intval',$input['active_city'])) . ")";
						$exe 		= $this->exeQuery($query);
					}
					if(count($active_pincode)>0 && $exe) {
						$query = 	"UPDATE ".PINCODE_TBL." SET active_delivery='1' WHERE id IN (" . implode(',', array_map('intval',$input['active_pincode'])) . ")";
						$exe 		= $this->exeQuery($query);
					}

					if($exe ){
						return 1;
					}else{
						return "Sorry!! Unexpected Error Occurred. Please try again.";
					}		
			}else{
				return $validate_csrf;
			}
		}

		function inactiveDeliveryStatus()
		{
			$query = 	"UPDATE ".STATE_TBL." SET active_delivery='0' WHERE active_delivery='1'";
			$exe 		= $this->exeQuery($query);
			$query2 = 	"UPDATE ".CITY_TBL." SET active_delivery='0' WHERE active_delivery='1'";
			$exe 		= $this->exeQuery($query2);
			$query3 = 	"UPDATE ".PINCODE_TBL." SET active_delivery='0' WHERE active_delivery='1'";
			$exe 		= $this->exeQuery($query3);

			if($exe){
				return 1;
			}
		}

		// pincode status

		function updatePincodeDeliveryStatus($input)
		{	
			$validate_csrf = $this->validateCSRF($input);
			if($validate_csrf=='success'){
					$admin_id 	= $_SESSION["ecom_admin_id"];
					$curr 		= date("Y-m-d H:i:s");

					$inactive_status = $this->inactivePincodeDeliveryStatus();

					if(isset($input['active_pincode'])) {
						$active_pincode = $input['active_pincode'];
					}else {
						$active_pincode = [];
					}
					
					$exe = 1;

					if(count($active_pincode)>0 && $exe) {
						$query = 	"UPDATE ".PINCODE_TBL." SET active_delivery='1' WHERE id IN (" . implode(',', array_map('intval',$input['active_pincode'])) . ")";
						$exe 		= $this->exeQuery($query);
					}

					if($exe ){
						return 1;
					}else{
						return "Sorry!! Unexpected Error Occurred. Please try again.";
					}		
			}else{
				return $validate_csrf;
			}
		}

		function inactivePincodeDeliveryStatus()
		{
			$query = 	"UPDATE ".PINCODE_TBL." SET active_delivery='0' WHERE active_delivery='1'";
			$exe 		= $this->exeQuery($query);

			if($exe){
				return 1;
			}
		}

		// City and Pin Code status changes depends upon State status

		function activeState($data) 
		{	
			$status = ($data['value']=='true') ? 1 : 0 ;
			$query = 	"UPDATE ".STATE_TBL." SET active_delivery='".$status."' WHERE id='".$data['option']."' ";
			$exe 		= $this->exeQuery($query);
			$query = 	"UPDATE ".CITY_TBL." SET active_delivery='".$status."' WHERE state_id='".$data['option']."' ";
			$exe 		= $this->exeQuery($query);
			$query = 	"UPDATE ".PINCODE_TBL." SET active_delivery='".$status."' WHERE state_id='".$data['option']."' ";
			$exe 		= $this->exeQuery($query);
		}

		// Pin Code status changes depends upon City status

		function activeCity($data) 
		{	
			$status 	= ($data['value']=='true') ? 1 : 0 ;
			$query 	= 	"UPDATE ".CITY_TBL." SET active_delivery='".$status."' WHERE city_id='".$data['option']."' ";
			$exe 		= $this->exeQuery($query);
			$query = 	"UPDATE ".PINCODE_TBL." SET active_delivery='".$status."' WHERE city_id='".$data['option']."' ";
			$exe 		= $this->exeQuery($query);
		}

		/*-----------------------------------------------
				Manage Notification Emails
		------------------------------------------------*/

		// Manage Notification Emails

		function manageNotificationEmail() 
		{
			$layout = "";
	    	$q = "SELECT * FROM ".NOTIFICATION_EMAIL_TBL." WHERE delete_status='0'  ORDER BY id DESC" ;
 		    $exe = $this->exeQuery($q);
 		    if(mysqli_num_rows($exe)>0){
 		    	$i=1;
 		    	while($list = mysqli_fetch_array($exe)){
		    		$status = (($list['status']==1) ? "On" : "Off"); 
		    		$status_c = (($list['status']==1) ? "checked" : " ");
					$status_class = (($list['status']==1) ? "text-success" : "text-danger");
					$td_data 	  = " data-formclass='edit_blog_category' data-form='addNotificationEmail' data-option='".$this->encryptData($list['id'])."'";
		    		$layout .= "
	    				<tr class='nk-tb-item'>
	    					<td class='nk-tb-col openEditNotificationEmail' $td_data>
                                ".$i."
                            </td>
                            <td class='nk-tb-col openEditNotificationEmail' $td_data>
                                ".$list['id']."
                            </td>
                            <td class='nk-tb-col tb-col-md openEditNotificationEmail' $td_data>
                                <span class='text-primary'>".$this->publishContent($list['email'])."</span>
	                        </td>    	                            
                            <td class='nk-tb-col tb-col-md'>
                            <div class='custom-control custom-switch'>
                                <input type='checkbox' class='custom-control-input changeNotificationEmailStatus' data-option='".$this->encryptData($list['id'])."' value='0' id='status_".$i."' name='save_as_draft' $status_c>
                                <label class='custom-control-label ' for='status_".$i."'><span class='$status_class'>$status </span></label>
                            </div>
                        	</td>
                            <td class='nk-tb-col nk-tb-col-tools'>
                                <ul class='nk-tb-actions gx-1'>
                                    <li class='nk-tb-action-hidden'>
                                        <button class='btn btn-trigger btn-icon deleteNotificationEmail' data-formclass='edit_blog_category' data-form='editNotificationEmail' data-option='".$this->encryptData($list['id'])."' ><em class='icon ni ni-trash-fill'></em></button>
                                    </li>
                                </ul>
                        	</td>
                        </tr>";
		    		$i++;
		    	}
 		    }
 		    return $layout;
		}

		// Add Notification Emails

		function addNotificationEmail($input)
		{	

			$validate_csrf = $this->validateCSRF($input);
			if($validate_csrf=='success') {
				$data = $this->cleanStringData($input);
				$check  = $this->check_query(NOTIFICATION_EMAIL_TBL,"id"," email='".$data['email']."' ");
				if ($check==0) {
					$admin_id 		= $_SESSION["ecom_admin_id"];
					$curr 			= date("Y-m-d H:i:s");
					$query = "INSERT INTO ".NOTIFICATION_EMAIL_TBL." SET 
								email 			= '".$data['email']."',
								status			= '1',
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
					$info = $this->getDetails(NOTIFICATION_EMAIL_TBL,"id,delete_status"," email='".$data['email']."' ");
					if ($info['delete_status']==1) {
						return "The entered email already present on the trash. Kindly restore it from the trash section.";
					}else{
						return "The entered email already exists. ";
					}
				}
			}else{
				return $validate_csrf;
			}
		}

		// Get Notification Emails Details

		function getNotificationEmailDetails($data)
		{	
			$layout = "";
			$id     = $this->decryptData($data);
			$query  = "SELECT * FROM  ".NOTIFICATION_EMAIL_TBL."  WHERE id='$id' ";
			$exe 	= $this->exeQuery($query);

			if(mysqli_num_rows($exe)) 
			{
				while ($list = mysqli_fetch_assoc($exe)) {
					$layout .= "<input type='hidden' name='token' value='".$this->encryptData($list['id'])."' id='token'>
					            <div class='form-group'>
					                <label class='form-label'>Notifiaction Email
					                    <en>*</en>
					                </label>
					                <input type='text' name='email' id='email' value='".$list['email']."'  class='form-control' placeholder='Enter Notifiaction Email' required>
					            </div>
					            <div class='form-error'>
					            </div>
					            <div class='form-group'>
					                <p class='float-right model_pt'>
					                <button type='button' class='btn btn-light close_modal' data-modal_id='editNotificationEmailModal'>Cancel</button>
					                <button type='submit' class='btn btn-primary'>Submit</button>
					                </p>
					            </div>";
				}
			}

			return $layout;
		}

		// Edit Notification Emails

		function editNotificationEmail($input)
		{	
			$validate_csrf = $this->validateCSRF($input);
			if($validate_csrf=='success'){
				$curr 		= date("Y-m-d H:i:s");
				$data = $this->cleanStringData($input);
				$id 		=	$this->decryptData($input['token']);
				$check  = $this->check_query(NOTIFICATION_EMAIL_TBL,"id"," email='".$data['email']."' AND id!='$id' ");
				if ($check==0) {
					$query 		= "UPDATE ".NOTIFICATION_EMAIL_TBL." SET 
								email 	   = '".$data['email']."',
								updated_at = '$curr' WHERE id='$id' ";
					$exe 		= $this->exeQuery($query);
					if($exe){
						return 1;
					}else{
						return "Sorry!! Unexpected Error Occurred. Please try again.";
					}
				}else{
					$info = $this->getDetails(NOTIFICATION_EMAIL_TBL,"id,delete_status"," email='".$data['email']."' ");
					if ($info['delete_status']==1) {
						return "The entered email already present on the trash. Kindly restore it from the trash section.";
					}else{
						return "The entered email already exists. ";
					}
				}	
			}else{
				return $validate_csrf;
			}
		}

		// Change Notification Emails Status

		function changeNotificationEmailStatus($data)
		{
			$data = $this->decryptData($data);
			$info = $this -> getDetails(NOTIFICATION_EMAIL_TBL,"status"," id ='$data' ");
			if($info['status'] ==1){
				$query = "UPDATE ".NOTIFICATION_EMAIL_TBL." SET status='0' WHERE id='$data' ";
			}else{
				$query = "UPDATE ".NOTIFICATION_EMAIL_TBL." SET status='1' WHERE id='$data' ";
			}
			$up_exe = $this->exeQuery($query);
			if($up_exe){
				return 1;
			}
		}

		// Update Notification Emails delete status

		function deleteNotificationEmail($data)
		{
			$data = $this->decryptData($data);
			$info = $this -> getDetails(NOTIFICATION_EMAIL_TBL,"delete_status"," id ='$data' ");
			$query = "UPDATE ".NOTIFICATION_EMAIL_TBL." SET delete_status='1' WHERE id='$data' ";
			$up_exe = $this->exeQuery($query);
			if($up_exe){
				return 1;
			}
		}

		// Restore Bolg Category

		function restoreNotificationEmail($data)
		{	
			$data = $this->decryptData($data);
			$query = "UPDATE ".NOTIFICATION_EMAIL_TBL." SET delete_status='0' WHERE id='$data' ";
			$up_exe = $this->exeQuery($query);
			if($up_exe) {
				return 1;
			}
		}

		/*--------------------------------------------- 
				Manage Return Reason Settings
		----------------------------------------------*/ 	

		function manageReturnReason()
 	  	{
 	  		$layout = "";
	    	$q 	 = "SELECT * FROM ".RETURN_REASON_TBL." WHERE delete_status='0'  ORDER BY id ASC" ;
 		    $exe = $this->exeQuery($q);
 		    if(mysqli_num_rows($exe)>0){
 		    	$i=1;
 		    	while($list = mysqli_fetch_array($exe)){
		    		$status 	  = (($list['status']==1) ? "Active" : "Inactive"); 
		    		$status_c 	  = (($list['status']==1) ? "checked" : " ");
					$status_class = (($list['status']==1) ? "text-success" : "text-danger");
					$td_data	  = "data-formclass='edit_return_reason' data-form='editReturnReason' data-option='".$this->encryptData($list['id'])."'";
		    		$layout .= "
		    				<tr class='nk-tb-item'>
		    					<td class='nk-tb-col openEditReturnReason' $td_data>
	                                ".$i."
	                            </td>
	                            <td class='nk-tb-col openEditReturnReason' $td_data>
	                                ".$list['id']."
	                            </td>
	                            <td class='nk-tb-col tb-col-md openEditReturnReason' $td_data>
	                                <span class='text-primary'>".$this->publishContent($list['return_reason'])."</span>
 	                            </td>
 	                            <td class='nk-tb-col tb-col-md'>
		                            <div class='custom-control custom-switch'>
	                                    <input type='checkbox' class='custom-control-input changeReturnReasonStatus' data-option='".$this->encryptData($list['id'])."' value='0' id='status_".$i."' save_as_draft' $status_c>
	                                    <label class='custom-control-label ' for='status_".$i."'><span class='$status_class'>$status </span></label>
	                                </div>
	                        	</td>
 	                            <td class='nk-tb-col nk-tb-col-tools'>
	                                <ul class='nk-tb-actions gx-1'>
	                                    <li class='nk-tb-action-hidden'>
	                                        <button class='btn btn-trigger btn-icon deleteReturnReason' data-option='".$this->encryptData($list['id'])."' ><em class='icon ni ni-trash-fill'></em></button>
	                                    </li>
	                                </ul>
                        	    </td>
	                        </tr>";
		    		$i++;
		    	}
 		    }
 		    return $layout;
 		}

 		/*--------------------------------------------- 
				Return Reason Master
		----------------------------------------------*/ 	
		
		// Add Return Reason 

		function addReturnReasones($input)
		{
			$data 		   = $this->cleanStringData($input);
			$validate_csrf = $this->validateCSRF($data);
			if ($validate_csrf=="success") {
				$token = $this->hyphenize($data['return_reason']);
				$check_unique = $this->check_query(RETURN_REASON_TBL,"id","token='".$token."' AND delete_status='0' ");
				$check_trash_unique = $this->check_query(RETURN_REASON_TBL,"id","token='".$token."' AND delete_status='1' ");
				if($check_unique==0) {
					if($check_trash_unique==0) {
						$admin_id 		= $_SESSION["ecom_admin_id"];
						$curr 			= date("Y-m-d H:i:s");
						$query 			= "INSERT INTO ".RETURN_REASON_TBL." SET 
									token 			= '".$token."',
									return_reason 	= '".$data['return_reason']."',
									status			='1',
									added_by		= '$admin_id',	
									created_at 		= '$curr',
									updated_at 		= '$curr' ";
						$exe = $this->exeQuery($query);
						if($exe){
							$this->unSetCSRF($data['csrf_key']);
							return 1;
						}else{
							return "Sorry!! Unexpected Error Occurred. Please try again.";
						}
					} else {
						return "Entered return reason already exists in trash";
					}
				} else {
					return "Entered return reason already exists";
				}	
			}else{
				return $validate_csrf;
			}
		}

		// Change Return Reason Status

		function changeReturnReasonStatus($data)
		{
			$data = $this->decryptData($data);
			$info = $this -> getDetails(RETURN_REASON_TBL,"status"," id ='$data' ");
			if($info['status'] ==1){
				$query = "UPDATE ".RETURN_REASON_TBL." SET status='0' WHERE id='$data' ";
			}else{
				$query = "UPDATE ".RETURN_REASON_TBL." SET status='1' WHERE id='$data' ";
			}
			$up_exe = $this->exeQuery($query);
			//return $up_exe;
			if($up_exe){
				return 1;
			}
		}

		// Get Return Reason  Details

		function getReturnReasonDetails($data)
		{
			$layout = "";
			$id     = $this->decryptData($data);
			$query  = "SELECT * FROM  ".RETURN_REASON_TBL."  WHERE id='$id' ";
			$exe 	= $this->exeQuery($query);

			if(mysqli_num_rows($exe)){
				while ($list = mysqli_fetch_assoc($exe)) {
					$layout .= "<input type='hidden' name='token' value='".$this->encryptData($list['id'])."' id='token'>
					            <div class='form-group'>
					                <label class='form-label'>Return Reason
					                    <en>*</en>
					                </label>
					                <input type='text' name='return_reason' id='return_reason'  value='".$list['return_reason']."' class='form-control' placeholder='Enter Return Reason Name' required>
					            </div>
					            <div class='form-error'></div>
					            <div class='form-group'>
					                <p class='float-right model_pt'>
					                <button type='button' class='btn btn-light close_modal' data-modal_id='editReturnReasonModal'>Cancel</button>
					                <button type='submit' class='btn btn-primary'>Submit</button>
					                </p>
					            </div>";
				}
			}

			return $layout;
		}

		// Edit Return Reason 

		function editReturnReasones($data)
		{
			if(isset($_SESSION[$data['csrf_key']])){
				if($this->cleanString($data['csrf_token']) == $_SESSION[$data['csrf_key']]){
					$token = $this->hyphenize($data['return_reason']);
					$check_unique = $this->check_query(RETURN_REASON_TBL,"id","token='".$token."' AND delete_status='0' AND id!='".$this->decryptData($data['token'])."' ");
					$check_trash_unique = $this->check_query(RETURN_REASON_TBL,"id","token='".$token."' AND delete_status='1' AND id!='".$this->decryptData($data['token'])."' ");
					if($check_unique==0) {
						if($check_trash_unique==0) {
							$admin_id 		= $_SESSION["ecom_admin_id"];
							$id 		= $this->decryptData($data["token"]);
							$curr 			= date("Y-m-d H:i:s");
							$query 			= "UPDATE ".RETURN_REASON_TBL." SET 
										token 			= '".$this->hyphenize($data['return_reason'])."',
										return_reason 	= '".$this->cleanString($data['return_reason'])."',
										updated_at 		= '$curr' WHERE id='$id' ";
							$exe 	= $this->exeQuery($query);
							if($exe){
								$this->unSetCSRF($data['csrf_key']);
								return 1;
							}else{
								return "Sorry!! Unexpected Error Occurred. Please try again.";
							}	
						} else {
							return "Entered return reason already exists in trash";
						}
					} else {
						return "Entered return reason already exists";
					}
				}else{
					return "Sorry Invalid User Entry. Try Re login to the Panel to continue.";
				}
			}else{
				return "Sorry Invalid User Entry. Try Re login to the Panel to continue.";
			}
		}

		// Update delete status

		function deleteReturnReason($data)
		{
			$data = $this->decryptData($data);
			$info = $this -> getDetails(RETURN_REASON_TBL,"delete_status"," id ='$data' ");
			if($info['delete_status'] ==1){
				$query = "UPDATE ".RETURN_REASON_TBL." SET delete_status='0' WHERE id='$data' ";
			}else{
				$query = "UPDATE ".RETURN_REASON_TBL." SET delete_status='1' WHERE id='$data' ";
			}
			$up_exe = $this->exeQuery($query);
			if($up_exe){
				return 1;
			}
		}

		// Restore Return Reason 

		function restoreReturnReason($data)
		{	
			$data = $this->decryptData($data);
			$query = "UPDATE ".RETURN_REASON_TBL." SET delete_status='0' WHERE id='$data' ";
			$up_exe = $this->exeQuery($query);
			if($up_exe) {
				return 1;
			}
		}

		/*--------------------------------------------- 
			   Manage Order Response Status 
		----------------------------------------------*/ 	

		function manageOrderResponseStatus()
 	  	{
 	  		$layout = "";
	    	$q 	 = "SELECT * FROM ".ORDER_RESPONSE_STATUS_TBL." WHERE delete_status='0'  ORDER BY id ASC" ;
 		    $exe = $this->exeQuery($q);
 		    if(mysqli_num_rows($exe)>0){
 		    	$i=1;
 		    	while($list = mysqli_fetch_array($exe)){
		    		$status 	  = (($list['status']==1) ? "Active" : "Inactive"); 
		    		$status_c 	  = (($list['status']==1) ? "checked" : " ");
					$status_class = (($list['status']==1) ? "text-success" : "text-danger");
					$td_data 	  = "data-formclass='edit_order_response_status' data-form='editOrderResponseStatus' data-option='".$this->encryptData($list['id'])."' ";
		    		$layout .= "
		    				<tr class='nk-tb-item'>
		    					<td class='nk-tb-col openEditOrderResponseStatus' $td_data>
	                                ".$i."
	                            </td>
	                            <td class='nk-tb-col openEditOrderResponseStatus' $td_data>
	                                ".$list['id']."
	                            </td>
	                            <td class='nk-tb-col tb-col-md openEditOrderResponseStatus' $td_data>
	                                <span class='text-primary'>".$this->publishContent($list['response_status'])."</span>
 	                            </td>
 	                            <td class='nk-tb-col tb-col-md'>
		                            <div class='custom-control custom-switch'>
	                                    <input type='checkbox' class='custom-control-input changeOrderResponseStatusStatus' data-option='".$this->encryptData($list['id'])."' value='0' id='status_".$i."' save_as_draft' $status_c>
	                                    <label class='custom-control-label ' for='status_".$i."'><span class='$status_class'>$status </span></label>
	                                </div>
	                        	</td>
 	                            <td class='nk-tb-col nk-tb-col-tools'>
	                                <ul class='nk-tb-actions gx-1'>
	                                    <li class='nk-tb-action-hidden'>
	                                        <button class='btn btn-trigger btn-icon deleteOrderResponseStatus' data-option='".$this->encryptData($list['id'])."' ><em class='icon ni ni-trash-fill'></em></button>
	                                    </li>
	                                </ul>
                        	    </td>
	                        </tr>";
		    		$i++;
		    	}
 		    }
 		    return $layout;
 		}

		/*--------------------------------------------- 
			   Order Response Status Master
		----------------------------------------------*/ 	
		
		// Add Order Response Status 

		function addOrderResponseStatus($input)
		{
			$data 		   = $this->cleanStringData($input);
			$validate_csrf = $this->validateCSRF($data);
			if ($validate_csrf=="success") {
				$token = $this->hyphenize($data['response_status']);
				$check_unique = $this->check_query(ORDER_RESPONSE_STATUS_TBL,"id","token='".$token."' AND delete_status='0' ");
				$check_trash_unique = $this->check_query(ORDER_RESPONSE_STATUS_TBL,"id","token='".$token."' AND delete_status='1' ");
				if($check_unique==0) {
					if($check_trash_unique==0) {
						$admin_id 		= $_SESSION["ecom_admin_id"];
						$curr 			= date("Y-m-d H:i:s");
						$query 			= "INSERT INTO ".ORDER_RESPONSE_STATUS_TBL." SET 
									token 			= '".$token."',
									response_status = '".$data['response_status']."',
									status			='1',
									added_by		= '$admin_id',	
									created_at 		= '$curr',
									updated_at 		= '$curr' ";
						$exe = $this->exeQuery($query);
						if($exe){
							$this->unSetCSRF($data['csrf_key']);
							return 1;
						}else{
							return "Sorry!! Unexpected Error Occurred. Please try again.";
						}
					} else {
						return "Entered return reason already exists in trash";
					}
				} else {
					return "Entered return reason already exists";
				}	
			}else{
				return $validate_csrf;
			}
		}

		// Change Order Response Status Status

		function changeOrderResponseStatusStatus($data)
		{
			$data = $this->decryptData($data);
			$info = $this -> getDetails(ORDER_RESPONSE_STATUS_TBL,"status"," id ='$data' ");
			if($info['status'] ==1){
				$query = "UPDATE ".ORDER_RESPONSE_STATUS_TBL." SET status='0' WHERE id='$data' ";
			}else{
				$query = "UPDATE ".ORDER_RESPONSE_STATUS_TBL." SET status='1' WHERE id='$data' ";
			}
			$up_exe = $this->exeQuery($query);
			//return $up_exe;
			if($up_exe){
				return 1;
			}
		}

		// Get Order Response Status  Details

		function getOrderResponseStatusDetails($data)
		{	
			$layout = "";
			$id     = $this->decryptData($data);
			$query  = "SELECT * FROM  ".ORDER_RESPONSE_STATUS_TBL."  WHERE id='$id' ";
			$exe 	= $this->exeQuery($query);

			if(mysqli_num_rows($exe) > 0) {
				while($list = mysqli_fetch_assoc($exe)) {
					$layout .= "<input type='hidden' name='token' value='".$this->encryptData($list['id'])."' id='token'>
				                <div class='form-group'>
				                    <label class='form-label'>Order Response Status
				                        <en>*</en>
				                    </label>
				                    <input type='text' name='response_status' id='response_status' value='".$list['response_status']."'  class='form-control' placeholder='Enter Order Response Status Name' required>
				                </div>
				                <div class='form-error'>
				                </div>
				                <div class='form-group'>
				                    <p class='float-right model_pt'>
				                    <button type='button' class='btn btn-light close_modal' data-modal_id='editOrderResponseStatusModal'>Cancel</button>
				                    <button type='submit' class='btn btn-primary'>Submit</button>
				                    </p>
				                </div>";
				}
			}

			return $layout;
		}

		// Edit Order Response Status 

		function editOrderResponseStatus($data)
		{
			if(isset($_SESSION[$data['csrf_key']])){
				if($this->cleanString($data['csrf_token']) == $_SESSION[$data['csrf_key']]){
					$token = $this->hyphenize($data['response_status']);
					$check_unique = $this->check_query(ORDER_RESPONSE_STATUS_TBL,"id","token='".$token."' AND delete_status='0' AND id!='".$this->decryptData($data['token'])."' ");
					$check_trash_unique = $this->check_query(ORDER_RESPONSE_STATUS_TBL,"id","token='".$token."' AND delete_status='1' AND id!='".$this->decryptData($data['token'])."' ");
					if($check_unique==0) {
						if($check_trash_unique==0) {
							$admin_id 		= $_SESSION["ecom_admin_id"];
							$id 		= $this->decryptData($data["token"]);
							$curr 			= date("Y-m-d H:i:s");
							$query 			= "UPDATE ".ORDER_RESPONSE_STATUS_TBL." SET 
										token 			= '".$this->hyphenize($data['response_status'])."',
										response_status = '".$this->cleanString($data['response_status'])."',
										updated_at 		= '$curr' WHERE id='$id' ";
							$exe 	= $this->exeQuery($query);
							if($exe){
								$this->unSetCSRF($data['csrf_key']);
								return 1;
							}else{
								return "Sorry!! Unexpected Error Occurred. Please try again.";
							}	
						} else {
							return "Entered return reason already exists in trash";
						}
					} else {
						return "Entered return reason already exists";
					}
				}else{
					return "Sorry Invalid User Entry. Try Re login to the Panel to continue.";
				}
			}else{
				return "Sorry Invalid User Entry. Try Re login to the Panel to continue.";
			}
		}

		// Update delete status

		function deleteOrderResponseStatus($data)
		{
			$data = $this->decryptData($data);
			$info = $this -> getDetails(ORDER_RESPONSE_STATUS_TBL,"delete_status"," id ='$data' ");
			if($info['delete_status'] ==1){
				$query = "UPDATE ".ORDER_RESPONSE_STATUS_TBL." SET delete_status='0' WHERE id='$data' ";
			}else{
				$query = "UPDATE ".ORDER_RESPONSE_STATUS_TBL." SET delete_status='1' WHERE id='$data' ";
			}
			$up_exe = $this->exeQuery($query);
			if($up_exe){
				return 1;
			}
		}

		// Restore Order Response Status 

		function restoreOrderResponseStatus($data)
		{	
			$data = $this->decryptData($data);
			$query = "UPDATE ".ORDER_RESPONSE_STATUS_TBL." SET delete_status='0' WHERE id='$data' ";
			$up_exe = $this->exeQuery($query);
			if($up_exe) {
				return 1;
			}
		}

		/*--------------------------------------------- 
					Return Settings
		----------------------------------------------*/ 	

		function manageReturnSetting()
 	  	{
 	  		$layout = "";
	    	$q 	 = "SELECT * FROM ".RETURN_SETTINGS_TBL." WHERE delete_status='0'  ORDER BY id ASC" ;
 		    $exe = $this->exeQuery($q);
 		    if(mysqli_num_rows($exe)>0){
 		    	$i=1;
 		    	while($list = mysqli_fetch_array($exe)){

		    		$status 	  = (($list['status']==1) ? "Active" : "Inactive"); 
		    		$status_c 	  = (($list['status']==1) ? "checked" : " ");
					$status_class = (($list['status']==1) ? "text-success" : "text-danger");
					
					$return_setting = (($list['return_setting']=='days') ? "Day" : "Hour");
					
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

					$td_data = "data-option='".$this->encryptData($list['id'])."'";


		    		$layout .= "
		    				<tr class='nk-tb-item'>
		    					<td class='nk-tb-col openEditReturnSetting' $td_data>
	                                ".$i."
	                            </td>
	                            <td class='nk-tb-col tb-col-md openEditReturnSetting' $td_data>
	                                <span class='text-primary'>".$this->publishContent($return_setting)."</span>
 	                            </td>
 	                            <td class='nk-tb-col tb-col-md openEditReturnSetting' $td_data>
	                                <span class='text-primary'>".$this->publishContent($return_duration)."</span>
 	                            </td>
 	                            <td class='nk-tb-col tb-col-md'>
		                            <div class='custom-control custom-switch'>
	                                    <input type='checkbox' class='custom-control-input changeReturnSettingStatus' data-option='".$this->encryptData($list['id'])."' value='0' id='status_".$i."' save_as_draft' $status_c>
	                                    <label class='custom-control-label ' for='status_".$i."'><span class='$status_class'>$status </span></label>
	                                </div>
	                        	</td>
 	                            <td class='nk-tb-col nk-tb-col-tools'>
	                                <ul class='nk-tb-actions gx-1'>
	                                    <li class='nk-tb-action-hidden'>
	                                        <button class='btn btn-trigger btn-icon deleteReturnSetting' data-option='".$this->encryptData($list['id'])."' ><em class='icon ni ni-trash-fill'></em></button>
	                                    </li>
	                                </ul>
                        	    </td>
	                        </tr>";
		    		$i++;
		    	}
 		    }
 		    return $layout;
 		}
		
		// Add Return Reason 

		function addReturnSetting($input)
		{
			$data 		   = $this->cleanStringData($input);
			$validate_csrf = $this->validateCSRF($data);

			if ($validate_csrf=="success") {

				$check_hours_validation_in_trash  = 0;
				$check_days_validation_intrash    = 0;
				$check_hours_validation           = 0;
				$check_days_validation            = 0;
				$hours ="";
				$days  ="";
				

				if($data['returnSetttingType']=="hours") {
					$check_hours_validation = $this->check_query(RETURN_SETTINGS_TBL,"hours,minutes"," hours='".$data['hours']."' AND minutes='".$data['minutes']."'  AND delete_status='0' ");
					$check_hours_validation_in_trash = $this->check_query(RETURN_SETTINGS_TBL,"hours,minutes"," hours='".$data['hours']."' AND minutes='".$data['minutes']."' AND delete_status='1'  ");

					$hours =" hours 	= '".(int)$data['hours']."',
							  minutes 	= '".(int)$data['minutes']."',";
				} elseif($data['returnSetttingType']=="days") {
					$check_days_validation =  $this->check_query(RETURN_SETTINGS_TBL,"days","days='".$data['days_count']."' AND delete_status='0' ");
					$check_days_validation_intrash =  $this->check_query(RETURN_SETTINGS_TBL,"days","days='".$data['days_count']."'  AND delete_status='1' ");
					$days = "days 		= '".(int)$data['days_count']."',";
					
				}

				if($check_days_validation==0) {
					if ($check_hours_validation_in_trash==0) {
						if ($check_hours_validation==0) {
							if($check_days_validation_intrash==0) {
								$admin_id 		= $_SESSION["ecom_admin_id"];
								$curr 			= date("Y-m-d H:i:s");
								$query 			= "INSERT INTO ".RETURN_SETTINGS_TBL." SET 
											return_setting 	= '".$data['returnSetttingType']."',
											".$days."
											".$hours."
											status			='1',
											delete_status	='0',
											created_at 		= '$curr',
											updated_at 		= '$curr' ";
								$exe = $this->exeQuery($query);
								if($exe){
									$this->unSetCSRF($data['csrf_key']);
									return 1;
								}else{
									return "Sorry!! Unexpected Error Occurred. Please try again.";
								}
							} else {
								return "Entered return setting hours already exists with same hours value in trash";
							}
						} else {
							return "Entered return setting hours already exists with same hours value";
						}
					} else {
						return "Entered return setting hours already exists with same hours value in trash ";
					}
				} else{
					return "Entered return setting day already exists with same day value.";
				}	
			}else{
				return $validate_csrf;
			}
		}

		// Change Return Reason Status

		function changeReturnSettingStatus($data)
		{
			$data = $this->decryptData($data);
			$info = $this -> getDetails(RETURN_SETTINGS_TBL,"status"," id ='$data' ");
			if($info['status'] ==1){
				$query = "UPDATE ".RETURN_SETTINGS_TBL." SET status='0' WHERE id='$data' ";
			}else{
				$query = "UPDATE ".RETURN_SETTINGS_TBL." SET status='1' WHERE id='$data' ";
			}
			$up_exe = $this->exeQuery($query);
			//return $up_exe;
			if($up_exe){
				return 1;
			}
		}

		// Get Return Reason  Details

		function getReturnSettingDetails($data)
		{	
			$result = array();
			$layout = "";
			$id     = $this->decryptData($data);
			$query  = "SELECT * FROM  ".RETURN_SETTINGS_TBL."  WHERE id='$id' ";
			$exe 	= $this->exeQuery($query);

			if(mysqli_num_rows($exe) > 0) {
				while ($list = mysqli_fetch_assoc($exe)) {
					$return_setting = $list['return_setting'];
					$layout .= "<input type='hidden' name='token' value='".$this->encryptData($list['id'])."' id='token'>
				                <div class='form-group'>
				                    <label class='form-label'>Return Duration Type
				                        <en>*</en>
				                    </label>
				                    <div class='row'>
				                        <div class='col-md-2'>
				                            <div class='custom-control custom-control-sm custom-radio'>
				                                <input type='radio' class='custom-control-input edit_return_setting_type' name='editReturnSetttingType' value='days' id='customRadio8' >
				                                <label class='custom-control-label' for='customRadio8'>Days</label>
				                            </div>
				                        </div>
				                        <div class='col-md-6'>
				                            <div class='custom-control custom-control-sm custom-radio'>
				                                <input type='radio' class='custom-control-input edit_return_setting_type' name='editReturnSetttingType' value='hours' id='customRadio9'>
				                                <label class='custom-control-label' for='customRadio9'>Hours</label>
				                            </div>
				                        </div>
				                    </div>
				                </div>
				                <div class='form-group'>
				                    <label class='form-label'>Duration
				                        <en>*</en>
				                    </label>
				                     <div class='row'>
				                        <div class='col-md-6 edit_days_input'>
				                            <input type='text' name='days_count'  id='edit_days_count' value='".$list['days']."' class='form-control' placeholder='Enter Days'>
				                        </div>
				                        <div class='col-md-2 edit_hours_input display_none'>
				                            <label class='form-label'>Hours</label>
				                            <input type='text' name='hours'  id='edit_hours' value='".$list['hours']."' class='form-control' value='0'  placeholder='HH'>
				                        </div>
				                        <div class='col-md-2 edit_hours_input display_none'>
				                            <label class='form-label'>Minutes</label>
				                            <input type='text' name='minutes' id='edit_minutes' value='".$list['minutes']."'  class='form-control' value='0'  placeholder='MM'>
				                        </div>
				                    </div>
				                </div>
				                <div class='form-error'>
				                </div>
				                <div class='form-group'>
				                    <p class='float-right model_pt'>
				                    <button type='button' class='btn btn-light close_modal' data-modal_id='editReturnSettingModal'>Cancel</button>
				                    <button type='submit' class='btn btn-primary'>Submit</button>
				                    </p>
				                </div>";
				}
			}

			$result['layout'] = $layout;
			$result['return_setting'] = $return_setting;

			return $result;
		}

		// Edit Return Reason 

		function editReturnSetting($input)
		{
			$data 		   = $this->cleanStringData($input);
			$validate_csrf = $this->validateCSRF($data);
		    $id            = $this->decryptData($data["token"]);

			if ($validate_csrf=="success") {

				$check_hours_validation_in_trash  = 0;
				$check_days_validation_intrash    = 0;
				$check_hours_validation           = 0;
				$check_days_validation            = 0;
				$hours ="";
				$days  ="";
				

				if($data['editReturnSetttingType']=="hours") {
					$check_hours_validation = $this->check_query(RETURN_SETTINGS_TBL,"hours,minutes"," hours='".$data['hours']."' AND minutes='".$data['minutes']."'  AND delete_status='0' AND id!='".$id."' ");
					$check_hours_validation_in_trash = $this->check_query(RETURN_SETTINGS_TBL,"hours,minutes"," hours='".$data['hours']."' AND minutes='".$data['minutes']."' AND delete_status='1' AND id!='".$id."'  ");

					$hours =" hours 	= '".(int)$data['hours']."',
							  minutes 	= '".(int)$data['minutes']."',";
				} elseif($data['editReturnSetttingType']=="days") {
					$check_days_validation =  $this->check_query(RETURN_SETTINGS_TBL,"days","days='".$data['days_count']."' AND delete_status='0' AND id!='".$id."' ");
					$check_days_validation_intrash =  $this->check_query(RETURN_SETTINGS_TBL,"days","days='".$data['days_count']."'  AND delete_status='1' AND id!='".$id."' ");
					$days = "days 		= '".(int)$data['days_count']."',";
					
				}

				if($check_days_validation==0) {
					if ($check_hours_validation_in_trash==0) {
						if ($check_hours_validation==0) {
							if($check_days_validation_intrash==0) {
								$admin_id 		= $_SESSION["ecom_admin_id"];
								$curr 			= date("Y-m-d H:i:s");
								$admin_id 		= $_SESSION["ecom_admin_id"];
								$curr 			= date("Y-m-d H:i:s");
								$query 			= "UPDATE ".RETURN_SETTINGS_TBL." SET 
											return_setting 	= '".$data['editReturnSetttingType']."',
											".$days."
											".$hours."
											status			='1',
											delete_status	='0'
											WHERE id='$id' ";
								$exe 	= $this->exeQuery($query);
								if($exe){
									$this->unSetCSRF($data['csrf_key']);
									return 1;
								}else{
									return "Sorry!! Unexpected Error Occurred. Please try again.";
								}
							} else {
								return "Entered return setting hours already exists with same hours value in trash";
							}
						} else {
							return "Entered return setting hours already exists with same hours value";
						}
					} else {
						return "Entered return setting hours already exists with same hours value in trash ";
					}
				} else{
					return "Entered return setting day already exists with same day value.";
				}	
			}else{
				return $validate_csrf;
			}
		}

		

		// Update delete status

		function deleteReturnSetting($data)
		{
			$data = $this->decryptData($data);
			$info = $this -> getDetails(RETURN_SETTINGS_TBL,"delete_status"," id ='$data' ");
			if($info['delete_status'] ==1){
				$query = "UPDATE ".RETURN_SETTINGS_TBL." SET delete_status='0' WHERE id='$data' ";
			}else{
				$query = "UPDATE ".RETURN_SETTINGS_TBL." SET delete_status='1' WHERE id='$data' ";
			}
			$up_exe = $this->exeQuery($query);
			if($up_exe){
				return 1;
			}
		}

		// Restore Return Reason 

		function restoreReturnSetting($data)
		{	
			$data = $this->decryptData($data);
			$query = "UPDATE ".RETURN_SETTINGS_TBL." SET delete_status='0' WHERE id='$data' ";
			$up_exe = $this->exeQuery($query);
			if($up_exe) {
				return 1;
			}
		}

		/*--------------------------------------------- 
					Classified Profile Settings
		----------------------------------------------*/ 	

		function manageClassifiedProfile()
 	  	{
 	  		$layout = "";
	    	$q 	 = "SELECT * FROM ".CONTRACTOR_PROFILE_TBL." WHERE delete_status='0'  ORDER BY id ASC" ;
 		    $exe = $this->exeQuery($q);
 		    if(mysqli_num_rows($exe)>0){
 		    	$i=1;
 		    	while($list = mysqli_fetch_array($exe)){
		    		$status 	  = (($list['status']==1) ? "Active" : "Inactive"); 
		    		$status_c 	  = (($list['status']==1) ? "checked" : " ");
					$status_class = (($list['status']==1) ? "text-success" : "text-danger");
					$td_data      = " data-formclass='edit_return_reason' data-form='editClassifiedProfile' data-option='".$this->encryptData($list['id'])."' ";
		    		$layout .= "
		    				<tr class='nk-tb-item'>
		    					<td class='nk-tb-col openEditClassifiedProfile' $td_data>
	                                ".$i."
	                            </td>
	                            <td class='nk-tb-col openEditClassifiedProfile' $td_data>
	                                ".$list['id']."
	                            </td>
	                            <td class='nk-tb-col tb-col-md openEditClassifiedProfile' $td_data>
	                                <span class='text-primary'>".$this->publishContent($list['profile'])."</span>
 	                            </td>
 	                            <td class='nk-tb-col tb-col-md'>
		                            <div class='custom-control custom-switch'>
	                                    <input type='checkbox' class='custom-control-input changeClassifiedProfileStatus' data-option='".$this->encryptData($list['id'])."' value='0' id='status_".$i."' save_as_draft' $status_c>
	                                    <label class='custom-control-label ' for='status_".$i."'><span class='$status_class'>$status </span></label>
	                                </div>
	                        	</td>
 	                            <td class='nk-tb-col nk-tb-col-tools'>
	                                <ul class='nk-tb-actions gx-1'>
	                                    
	                                    <li class='nk-tb-action-hidden'>
	                                        <button class='btn btn-trigger btn-icon deleteClassifiedProfile' data-option='".$this->encryptData($list['id'])."' ><em class='icon ni ni-trash-fill'></em></button>
	                                    </li>
	                                </ul>
                        	    </td>
	                        </tr>";
		    		$i++;
		    	}
 		    }
 		    return $layout;
 		}
		
		// Add Classified Profile 

		function addClassifiedProfile($input,$image)
		{
			$data 		   = $this->cleanStringData($input);
			$validate_csrf = $this->validateCSRF($data);
			if ($validate_csrf=="success") {
				$token = $this->hyphenize($data['profile']);

				// Add Images

				if ($image['is_uploaded']) {
					$file_status = $image['status'];
					$image_q = "
						file_name 	    = '".$image['file_name']."',
						file_type 		= '".$image['file_type']."',
						file_size 		= '".$image['file_size']."',
						file_alt_name 	= '".$data['image_alt_name']."',
					";
				}else{
					$file_status = "ok";
					$image_q = "";
				}

				$check_unique = $this->check_query(CONTRACTOR_PROFILE_TBL,"id","token='".$token."' AND delete_status='0' ");
				$check_trash_unique = $this->check_query(CONTRACTOR_PROFILE_TBL,"id","token='".$token."' AND delete_status='1' ");
				if ($check_unique==0) {
					if($check_trash_unique==0) {
						$admin_id 		= $_SESSION["ecom_admin_id"];
						$curr 			= date("Y-m-d H:i:s");
						$query 			= "INSERT INTO ".CONTRACTOR_PROFILE_TBL." SET 
									token 			  = '".$token."',
									profile 	      = '".$data['profile']."',
									short_description = '".$data['short_description']."',
									status			  ='1',
									".$image_q."
									added_by		  = '$admin_id',	
									created_at 		  = '$curr',
									updated_at 		  = '$curr' ";
						$exe = $this->exeQuery($query);
						if($exe){
							$this->unSetCSRF($data['csrf_key']);
							return 1;
						}else{
							return "Sorry!! Unexpected Error Occurred. Please try again.";
						}
					} else {
							return "Entered profile name already exists in trash";
					}
				} else {
					return "Entered profile name already exists";
				}	
			}else{
				return $validate_csrf;
			}
		}

		// Change Classified Profile Status

		function changeClassifiedProfileStatus($data)
		{
			$data = $this->decryptData($data);
			$info = $this -> getDetails(CONTRACTOR_PROFILE_TBL,"status"," id ='$data' ");
			if($info['status'] ==1){
				$query = "UPDATE ".CONTRACTOR_PROFILE_TBL." SET status='0' WHERE id='$data' ";
			}else{
				$query = "UPDATE ".CONTRACTOR_PROFILE_TBL." SET status='1' WHERE id='$data' ";
			}
			$up_exe = $this->exeQuery($query);
			//return $up_exe;
			if($up_exe){
				return 1;
			}
		}

		// Get Classified Profile  Details

		function getClassifiedProfileDetails($data)
		{		
			$layout = "";
			$id     = $this->decryptData($data);
			$query  = "SELECT * FROM  ".CONTRACTOR_PROFILE_TBL."  WHERE id='$id' ";
			$exe    = $this->exeQuery($query);
	        $list   = mysqli_fetch_array($exe);
			$list['file'] = UPLOADS.$list['file_name'];   
	        return json_encode($list);   

	        if(mysqli_num_rows($exe) > 0) {
				while ($list = mysqli_fetch_assoc($exe)) {

					if($list['file_name']=='') {
						$image = "<div class='row'>
                                        <div class='form-group col-md-12'>
                                            <label class='form-label' >Image</label>
                                            <div class='form-control-wrap'>
                                                <div class='custom-file'>
                                                    <input type='file'  class='custom-file-input' name='file_1' id='file_1'  >
                                                    <label class='custom-file-label' for='file'>Choose file</label>
                                                    <input type='hidden' name='file_type' value='image' >
                                                </div>
                                                <p class='help_text'>".BANNER_IMAGE_HELP_TEXT."</p>
                                            </div>
                                        </div>
                                        <div class='form-group col-md-6'>
                                            <label class='form-label' > Image Name 
                                            </label>
                                            <input type='text' name='image_name' value='".$list['file_alt_name']."' id='image_name' class='form-control' placeholder='Enter name of the image' >
                                        </div>
                                        <div class='form-group col-md-6'>
                                            <label class='form-label' > Image Alt Name 
                                            </label>
                                            <input type='text' name='image_alt_name' value='".$list['file_alt_name']."' id='image_alt_name' class='form-control' placeholder='Enter alt name for the image' >
                                        </div>
                                    </div>";
					} else {
						$image = "<div class='row'>
                                        <div class='form-group col-md-4'>
                                            <div class='form-control-wrap'>
                                                <img src=".UPLOADS.$list['file_name']." class='img-thumbnail img-responsive' >
                                            </div>
                                        </div>
                                        <div class='col-md-8'>
                                            <div class='row'>
                                                <div class='form-group col-md-12'>
                                                    <label class='form-label' >Update Image</label>
                                                    <div class='form-control-wrap'>
                                                        <div class='custom-file'>
                                                            <input type='file'  class='custom-file-input' name='file' id='file'  >
                                                            <label class='custom-file-label' for='file'>Choose file</label>
                                                            <input type='hidden' name='file_type' value='image' >
                                                        </div>
                                                        <p class='help_text'>".BANNER_IMAGE_HELP_TEXT."</p>
                                                    </div>
                                                </div>
                                                <div class='form-group col-md-12'>
                                                    <label class='form-label' >New Image Name 
                                                    </label>
                                                    <input type='text' name='image_name' id='image_name' class='form-control' placeholder='Enter name of the image'  >
                                                </div>
                                                <div class='form-group col-md-6'>
                                                    <label class='form-label' > Current Image Name 
                                                    </label>
                                                    <input type='text' value='".$list['file_name']."'  class='form-control' placeholder='Enter name of the image' disabled >
                                                </div>
                                                <div class='form-group col-md-6'>
                                                    <label class='form-label' > Image Alt Name 
                                                    </label>
                                                    <input type='text' name='image_alt_name' value='".$list['file_alt_name']."' id='image_alt_name' class='form-control' placeholder='Enter alt name for the image' >
                                                </div>
                                            </div>
                                        </div>
                                    </div>";
					}

					$layout .= "
                    		<input type='hidden' name='token' id='token' value='".$this->encryptData($list['id'])."'>
			                <div class='form-group'>
			                    <label class='form-label'>Classified Profile
			                        <en>*</en>
			                    </label>
			                    <input type='text' name='profile' id='title_name' value='".$list['profile']."' class='form-control' placeholder='Enter Classified Profile Classes Name' required>
			                </div>
			                 <div class='form-group'>
                                <label class='form-label'>Short Description <em>*</em></label>
                            <textarea class='form-control' rows='2'  name='short_description' placeholder='Short Description'>".$list['short_description']."</textarea>
                        </div>
			                ".$image."
			                <div class='form-group'>
				                <p class='float-right model_pt'>
		                            <button type='button' class='btn btn-light close_modal' data-modal_id='editClassifiedProfileModal'>Cancel</button>
		                            <button type='submit' class='btn btn-primary'>Update</button>
		                        </p>
			                </div>
			            
					";
				}
			}  else {
				return "Sorry!! Unexpected Error Occurred. Please try again.";
			}

		}

		// Edit Classified Profile 

		function editClassifiedProfile($data,$image)
		{	
			if(isset($_SESSION[$data['csrf_key']])){
				if($this->cleanString($data['csrf_token']) == $_SESSION[$data['csrf_key']]){

					// Add Images

					if ($image['is_uploaded']) {
						$file_status = $image['status'];
						$image_q = "
							file_name 	= '".$image['file_name']."',
							file_type 		= '".$image['file_type']."',
							file_size 		= '".$image['file_size']."',
							file_alt_name 	= '".$data['image_alt_name']."',
						";

						// Remove previous image
						$info = $this->getDetails(CONTRACTOR_PROFILE_TBL,"file_name"," id='".$data["token"]."' ");
						if (isset($info['file_name'])) {
							unlink("./resource/uploads/".$info['file_name']);
						}
					}else{
						$file_status = "ok";
						$image_q = "";
					}

					$admin_id 	= $_SESSION["ecom_admin_id"];
					$id 		= $data["contractor_id"];
					$token 		= $data["token"];
					$curr 		= date("Y-m-d H:i:s");
					
					$check_unique = $this->check_query(CONTRACTOR_PROFILE_TBL,"id","token='".$token."' AND id!='".$id."' AND delete_status='0' ");
					$check_trash_unique = $this->check_query(CONTRACTOR_PROFILE_TBL,"id","token='".$token."' AND id!='".$id."' AND delete_status='1' ");
					if ($check_unique==0) {
						if($check_trash_unique==0) {
							$query 		= "UPDATE ".CONTRACTOR_PROFILE_TBL." SET 
										token 			  = '".$token."',
										".$image_q."
										profile 		  = '".$data["profile"]."',
										short_description = '".$data['short_description']."',
										updated_at 		  = '$curr' WHERE id='$id' ";
							$exe 	= $this->exeQuery($query);
							if($exe){
								$this->unSetCSRF($data['csrf_key']);
								return 1;
							} else {
								return "Sorry!! Unexpected Error Occurred. Please try again.";
							}
						} else {
							return "Entered profile name already exists in trash";
						}
					} else {
						return "Entered profile name already exists";
					}	
				}else{
					return "Sorry Invalid User Entry. Try Re login to the Panel to continue.";
				}
			} else {  return "Sorry Invalid User Entry. Try Re login to the Panel to continue.";
			}
		}

		// Update delete status

		function deleteClassifiedProfile($data)
		{
			$data = $this->decryptData($data);
			$info = $this -> getDetails(CONTRACTOR_PROFILE_TBL,"delete_status"," id ='$data' ");
			if($info['delete_status'] ==1){
				$query = "UPDATE ".CONTRACTOR_PROFILE_TBL." SET delete_status='0' WHERE id='$data' ";
			}else{
				$query = "UPDATE ".CONTRACTOR_PROFILE_TBL." SET delete_status='1' WHERE id='$data' ";
			}
			$up_exe = $this->exeQuery($query);
			if($up_exe){
				return 1;
			}
		}

		// Restore Classified Profile 

		function restoreClassifiedProfile($data)
		{	
			$data = $this->decryptData($data);
			$query = "UPDATE ".CONTRACTOR_PROFILE_TBL." SET delete_status='0' WHERE id='$data' ";
			$up_exe = $this->exeQuery($query);
			if($up_exe) {
				return 1;
			}
		}

		/*--------------------------------------------- 
					Product Request Status Settings
		----------------------------------------------*/ 	

		function manageProductRequestStatus()
 	  	{
 	  		$layout = "";
	    	$q 	 = "SELECT * FROM ".PRODUCT_REQUEST_STATUS_TBL." WHERE delete_status='0'  ORDER BY id ASC" ;
 		    $exe = $this->exeQuery($q);
 		    if(mysqli_num_rows($exe)>0){
 		    	$i=1;
 		    	while($list = mysqli_fetch_array($exe)){
		    		$status 	  = (($list['status']==1) ? "Active" : "Inactive"); 
		    		$status_c 	  = (($list['status']==1) ? "checked" : " ");
					$status_class = (($list['status']==1) ? "text-success" : "text-danger");
					$td_data 	  = "data-formclass='edit_product_request_status' data-form='editProductRequestStatus' data-option='".$this->encryptData($list['id'])."'";
		    		$layout .= "
		    				<tr class='nk-tb-item'>
		    					<td class='nk-tb-col openEditProductRequestStatus' $td_data>
	                                ".$i."
	                            </td>
	                            <td class='nk-tb-col openEditProductRequestStatus' $td_data>
	                                ".$list['id']."
	                            </td>
	                            <td class='nk-tb-col tb-col-md openEditProductRequestStatus' $td_data>
	                                <span class='text-primary'>".$this->publishContent($list['request_status'])."</span>
 	                            </td>
 	                            <td class='nk-tb-col tb-col-md'>
		                            <div class='custom-control custom-switch'>
	                                    <input type='checkbox' class='custom-control-input changeProductRequestStatusMasterStatus' data-option='".$this->encryptData($list['id'])."' value='0' id='status_".$i."' save_as_draft' $status_c>
	                                    <label class='custom-control-label ' for='status_".$i."'><span class='$status_class'>$status </span></label>
	                                </div>
	                        	</td>
 	                            <td class='nk-tb-col nk-tb-col-tools'>
	                                <ul class='nk-tb-actions gx-1'>
	                                    <li class='nk-tb-action-hidden'>
	                                        <button class='btn btn-trigger btn-icon deleteProductRequestStatus' data-option='".$this->encryptData($list['id'])."' ><em class='icon ni ni-trash-fill'></em></button>
	                                    </li>
	                                </ul>
                        	    </td>
	                        </tr>";
		    		$i++;
		    	}
 		    }
 		    return $layout;
 		}
		
		// Add Product Request Status 

		function addProductRequestStatus($input)
		{
			$data 		   = $this->cleanStringData($input);
			$validate_csrf = $this->validateCSRF($data);
			if ($validate_csrf=="success") {
				$token = $this->hyphenize($data['request_status']);
				$check_unique = $this->check_query(PRODUCT_REQUEST_STATUS_TBL,"id","token='".$token."' AND delete_status='0' ");
				$check_trash_unique = $this->check_query(PRODUCT_REQUEST_STATUS_TBL,"id","token='".$token."' AND delete_status='1' ");
				if($check_unique==0) {
					if($check_trash_unique==0) {
						$admin_id 		= $_SESSION["ecom_admin_id"];
						$curr 			= date("Y-m-d H:i:s");
						$query 			= "INSERT INTO ".PRODUCT_REQUEST_STATUS_TBL." SET 
									token 			= '".$token."',
									request_status 	= '".$data['request_status']."',
									status			='1',
									added_by		= '$admin_id',	
									created_at 		= '$curr',
									updated_at 		= '$curr' ";
						$exe = $this->exeQuery($query);
						if($exe){
							$this->unSetCSRF($data['csrf_key']);
							return 1;
						}else{
							return "Sorry!! Unexpected Error Occurred. Please try again.";
						}
					} else {
						return "Entered Product Request Status already exists in trash";
					}
				} else {
					return "Entered Product Request Status already exists";
				}	
			}else{
				return $validate_csrf;
			}
		}

		// Change Product Request Status 

		function changeProductRequestStatusMasterStatus($data)
		{
			$data = $this->decryptData($data);
			$info = $this -> getDetails(PRODUCT_REQUEST_STATUS_TBL,"status"," id ='$data' ");
			if($info['status'] ==1){
				$query = "UPDATE ".PRODUCT_REQUEST_STATUS_TBL." SET status='0' WHERE id='$data' ";
			}else{
				$query = "UPDATE ".PRODUCT_REQUEST_STATUS_TBL." SET status='1' WHERE id='$data' ";
			}
			$up_exe = $this->exeQuery($query);
			//return $up_exe;
			if($up_exe){
				return 1;
			}
		}

		// Get Product Request Status  Details

		function getProductRequestStatusDetails($data)
		{	
			$layout = "";
			$id     = $this->decryptData($data);
			$query  = "SELECT * FROM  ".PRODUCT_REQUEST_STATUS_TBL."  WHERE id='$id' ";
			$exe 	= $this->exeQuery($query);

			if(mysqli_num_rows($exe) > 0) {
				while ($list = mysqli_fetch_assoc($exe)) {
					$layout .= "<input type='hidden' name='token' value='".$this->encryptData($list['id'])."' id='token'>
			                    <div class='form-group'>
			                        <label class='form-label'>Request Status
			                            <en>*</en>
			                        </label>
			                        <input type='text' name='request_status' id='request_status'  value='".$list['request_status']."' class='form-control' placeholder='Enter Request Status Classes Name' required>
			                    </div>
				                <div class='form-error'>
				                </div>
				                <div class='form-group'>
				                    <p class='float-right model_pt'>
				                    <button type='button' class='btn btn-light close_modal' data-modal_id='editProductRequestStatusModal'>Cancel</button>
				                    <button type='submit' class='btn btn-primary'>Submit</button>
				                    </p>
				                </div>";
				}
			}

			return $layout;
		}

		// Edit Product Request Status 

		function editProductRequestStatus($data)
		{	
			$validate_csrf = $this->validateCSRF($data);
			if($validate_csrf=='success'){
				$token = $this->hyphenize($data['request_status']);
				$check_unique = $this->check_query(PRODUCT_REQUEST_STATUS_TBL,"id","token='".$token."' AND delete_status='0' AND id!='".$this->decryptData($data['token'])."' ");
				$check_trash_unique = $this->check_query(PRODUCT_REQUEST_STATUS_TBL,"id","token='".$token."' AND delete_status='1' AND id!='".$this->decryptData($data['token'])."' ");
				if($check_unique==0) {
					if($check_trash_unique==0) {
						$admin_id 		= $_SESSION["ecom_admin_id"];
						$id 		= $this->decryptData($data["token"]);
						$curr 			= date("Y-m-d H:i:s");
						$query 			= "UPDATE ".PRODUCT_REQUEST_STATUS_TBL." SET 
									token 			= '".$this->hyphenize($data['request_status'])."',
									request_status 	= '".$this->cleanString($data['request_status'])."',
									updated_at 		= '$curr' WHERE id='$id' ";
						$exe 	= $this->exeQuery($query);
						if($exe){
							$this->unSetCSRF($data['csrf_key']);
							return 1;
						}else{
							return "Sorry!! Unexpected Error Occurred. Please try again.";
						}	
					} else {
						return "Entered Product Request Status already exists in trash";
					}
				} else {
					return "Entered Product Request Status already exists";
				}
			}else{
				return "Sorry Invalid User Entry. Try Re login to the Panel to continue.";
			}
			
		}

		// Update delete status

		function deleteProductRequestStatus($data)
		{
			$data = $this->decryptData($data);
			$info = $this -> getDetails(PRODUCT_REQUEST_STATUS_TBL,"delete_status"," id ='$data' ");
			if($info['delete_status'] ==1){
				$query = "UPDATE ".PRODUCT_REQUEST_STATUS_TBL." SET delete_status='0' WHERE id='$data' ";
			}else{
				$query = "UPDATE ".PRODUCT_REQUEST_STATUS_TBL." SET delete_status='1' WHERE id='$data' ";
			}
			$up_exe = $this->exeQuery($query);
			if($up_exe){
				return 1;
			}
		}

		// Restore Product Request Status 

		function restoreProductRequestStatus($data)
		{	
			$data = $this->decryptData($data);
			$query = "UPDATE ".PRODUCT_REQUEST_STATUS_TBL." SET delete_status='0' WHERE id='$data' ";
			$up_exe = $this->exeQuery($query);
			if($up_exe) {
				return 1;
			}
		}


		/*--------------------------------------------- 
			Product Unit Settings
		----------------------------------------------*/ 	

		function manageProductUnit()
 	  	{
 	  		$layout = "";
	    	$q 	 = "SELECT * FROM ".PRODUCT_UNIT_TBL." WHERE delete_status='0'  ORDER BY id ASC" ;
 		    $exe = $this->exeQuery($q);
 		    if(mysqli_num_rows($exe)>0){
 		    	$i=1;
 		    	while($list = mysqli_fetch_array($exe)){
		    		$status 	  = (($list['status']==1) ? "Active" : "Inactive"); 
		    		$status_c 	  = (($list['status']==1) ? "checked" : " ");
					$status_class = (($list['status']==1) ? "text-success" : "text-danger");
					$td_data 	  = "data-option='".$this->encryptData($list['id'])."'";
		    		$layout .= "
		    				<tr class='nk-tb-item'>
		    					<td class='nk-tb-col openEditProductUnit' $td_data>
	                                ".$i."
	                            </td>
	                            <td class='nk-tb-col openEditProductUnit' $td_data>
	                                ".$list['id']."
	                            </td>
	                            <td class='nk-tb-col tb-col-md openEditProductUnit' $td_data>
	                                <span class='text-primary'>".$this->publishContent($list['product_unit'])."</span>
 	                            </td>
 	                            <td class='nk-tb-col tb-col-md'>
		                            <div class='custom-control custom-switch'>
	                                    <input type='checkbox' class='custom-control-input changeProductUnitMasterStatus' data-option='".$this->encryptData($list['id'])."' value='0' id='status_".$i."' save_as_draft' $status_c>
	                                    <label class='custom-control-label ' for='status_".$i."'><span class='$status_class'>$status </span></label>
	                                </div>
	                        	</td>
 	                            <td class='nk-tb-col nk-tb-col-tools'>
	                                <ul class='nk-tb-actions gx-1'>
	                                    <li class='nk-tb-action-hidden'>
	                                        <button class='btn btn-trigger btn-icon deleteProductUnit' data-option='".$this->encryptData($list['id'])."' ><em class='icon ni ni-trash-fill'></em></button>
	                                    </li>
	                                </ul>
                        	    </td>
	                        </tr>";
		    		$i++;
		    	}
 		    }
 		    return $layout;
 		}

		/*--------------------------------------------- 
				Display Tag Settings
		----------------------------------------------*/ 	

		function manageDisplayTag()
 	  	{
 	  		$layout = "";
	    	$q 	 = "SELECT * FROM ".PRODUCT_DISPLAY_TAG." WHERE delete_status='0'  ORDER BY id ASC" ;
 		    $exe = $this->exeQuery($q);
 		    if(mysqli_num_rows($exe)>0){
 		    	$i=1;
 		    	while($list = mysqli_fetch_array($exe)){
		    		$status 	  = (($list['status']==1) ? "Active" : "Inactive"); 
		    		$status_c 	  = (($list['status']==1) ? "checked" : " ");
					$status_class = (($list['status']==1) ? "text-success" : "text-danger");
					$td_data 	  = "data-formclass='edit_product_request_status' data-form='editDisplayTag' data-option='".$this->encryptData($list['id'])."'";
		    		$layout .= "
		    				<tr class='nk-tb-item'>
		    					<td class='nk-tb-col openEditDisplayTag' $td_data>
	                                ".$i."
	                            </td>
	                            <td class='nk-tb-col openEditDisplayTag' $td_data>
	                                ".$list['id']."
	                            </td>
	                            <td class='nk-tb-col tb-col-md openEditDisplayTag' $td_data>
	                                <span class='text-primary'>".$this->publishContent($list['display_tag'])."</span>
 	                            </td>
 	                            <td class='nk-tb-col tb-col-md'>
		                            <div class='custom-control custom-switch'>
	                                    <input type='checkbox' class='custom-control-input changeDisplayTagMasterStatus' data-option='".$this->encryptData($list['id'])."' value='0' id='status_".$i."' save_as_draft' $status_c>
	                                    <label class='custom-control-label ' for='status_".$i."'><span class='$status_class'>$status </span></label>
	                                </div>
	                        	</td>
 	                            <td class='nk-tb-col nk-tb-col-tools'>
	                                <ul class='nk-tb-actions gx-1'>
	                                    <li class='nk-tb-action-hidden'>
	                                        <button class='btn btn-trigger btn-icon deleteDisplayTag' data-option='".$this->encryptData($list['id'])."' ><em class='icon ni ni-trash-fill'></em></button>
	                                    </li>
	                                </ul>
                        	    </td>
	                        </tr>";
		    		$i++;
		    	}
 		    }
 		    return $layout;
 		}

 		/*--------------------------------------------- 
			   Product Unit  Master
		----------------------------------------------*/ 
		
		// Add Product Unit 

		function addProductUnit($input)
		{
			$data 		   = $this->cleanStringData($input);
			$validate_csrf = $this->validateCSRF($data);
			if ($validate_csrf=="success") {
				$token = $this->hyphenize($data['product_unit']);
				$check_unique = $this->check_query(PRODUCT_UNIT_TBL,"id","token='".$token."' AND delete_status='0' ");
				$check_trash_unique = $this->check_query(PRODUCT_UNIT_TBL,"id","token='".$token."' AND delete_status='1' ");
				if($check_unique==0) {
					if($check_trash_unique==0) {
						$admin_id 		= $_SESSION["ecom_admin_id"];
						$curr 			= date("Y-m-d H:i:s");
						$query 			= "INSERT INTO ".PRODUCT_UNIT_TBL." SET 
									token 			= '".$token."',
									product_unit 	= '".$data['product_unit']."',
									status			= '1',
									added_by		= '$admin_id',	
									created_at 		= '$curr',
									updated_at 		= '$curr' ";
						$exe = $this->exeQuery($query);
						if($exe){
							$this->unSetCSRF($data['csrf_key']);
							return 1;
						}else{
							return "Sorry!! Unexpected Error Occurred. Please try again.";
						}
					} else {
						return "Entered Product Unit already exists in trash";
					}
				} else {
					return "Entered Product Unit already exists";
				}	
			}else{
				return $validate_csrf;
			}
		}

		// Change Product Unit 

		function changeProductUnitMasterStatus($data)
		{
			$data = $this->decryptData($data);
			$info = $this -> getDetails(PRODUCT_UNIT_TBL,"status"," id ='$data' ");
			if($info['status'] ==1){
				$query = "UPDATE ".PRODUCT_UNIT_TBL." SET status='0' WHERE id='$data' ";
			}else{
				$query = "UPDATE ".PRODUCT_UNIT_TBL." SET status='1' WHERE id='$data' ";
			}
			$up_exe = $this->exeQuery($query);
			//return $up_exe;
			if($up_exe){
				return 1;
			}
		}

		// Get Product Unit  Details

		function getProductUnitDetails($data)
		{	
			$layout = "";
			$id     = $this->decryptData($data);
			$query  = "SELECT * FROM  ".PRODUCT_UNIT_TBL."  WHERE id='$id' ";
			$exe 	= $this->exeQuery($query);

			if(mysqli_num_rows($exe) > 0) {
				while ($list = mysqli_fetch_assoc($exe)) {
					$layout .= "<input type='hidden' name='token' value='".$this->encryptData($list['id'])."' id='token'>
			                    <div class='form-group'>
			                        <label class='form-label'>Unit
			                            <en>*</en>
			                        </label>
			                        <input type='text' name='product_unit' id='product_unit'  value='".$list['product_unit']."' class='form-control' placeholder='Enter Unit Classes Name' required>
			                    </div>
				                <div class='form-error'>
				                </div>
				                <div class='form-group'>
				                    <p class='float-right model_pt'>
				                    <button type='button' class='btn btn-light close_modal' data-modal_id='editProductUnitModal'>Cancel</button>
				                    <button type='submit' class='btn btn-primary'>Submit</button>
				                    </p>
				                </div>";
				}
			}

			return $layout;
		}

		// Edit Product Unit 

		function editProductUnit($data)
		{	
			$validate_csrf = $this->validateCSRF($data);
			if($validate_csrf=='success'){
				$token = $this->hyphenize($data['product_unit']);
				$check_unique = $this->check_query(PRODUCT_UNIT_TBL,"id","token='".$token."' AND delete_status='0' AND id!='".$this->decryptData($data['token'])."' ");
				$check_trash_unique = $this->check_query(PRODUCT_UNIT_TBL,"id","token='".$token."' AND delete_status='1' AND id!='".$this->decryptData($data['token'])."' ");
				if($check_unique==0) {
					if($check_trash_unique==0) {
						$admin_id 		= $_SESSION["ecom_admin_id"];
						$id 		= $this->decryptData($data["token"]);
						$curr 			= date("Y-m-d H:i:s");
						$query 			= "UPDATE ".PRODUCT_UNIT_TBL." SET 
									token 			= '".$this->hyphenize($data['product_unit'])."',
									product_unit 	= '".$this->cleanString($data['product_unit'])."',
									updated_at 		= '$curr' WHERE id='$id' ";
						$exe 	= $this->exeQuery($query);
						if($exe){
							$this->unSetCSRF($data['csrf_key']);
							return 1;
						}else{
							return "Sorry!! Unexpected Error Occurred. Please try again.";
						}	
					} else {
						return "Entered Product Unit already exists in trash";
					}
				} else {
					return "Entered Product Unit already exists";
				}
			}else{
				return "Sorry Invalid User Entry. Try Re login to the Panel to continue.";
			}
		}

		// Update delete status

		function deleteProductUnit($data)
		{
			$data = $this->decryptData($data);
			$info = $this -> getDetails(PRODUCT_UNIT_TBL,"delete_status"," id ='$data' ");
			if($info['delete_status'] ==1){
				$query = "UPDATE ".PRODUCT_UNIT_TBL." SET delete_status='0' WHERE id='$data' ";
			}else{
				$query = "UPDATE ".PRODUCT_UNIT_TBL." SET delete_status='1' WHERE id='$data' ";
			}
			$up_exe = $this->exeQuery($query);
			if($up_exe){
				return 1;
			}
		}

		// Restore Product Unit 

		function restoreProductUnit($data)
		{	
			$data = $this->decryptData($data);
			$query = "UPDATE ".PRODUCT_UNIT_TBL." SET delete_status='0' WHERE id='$data' ";
			$up_exe = $this->exeQuery($query);
			if($up_exe) {
				return 1;
			}
		}

		/*--------------------------------------------- 
			   Display Tag  Master
		----------------------------------------------*/ 
		
		// Add Display Tag 

		function addDisplayTag($input)
		{	
			$data 		   = $this->cleanStringData($input);
			$validate_csrf = $this->validateCSRF($data);
			if ($validate_csrf=="success") {
				$token = $this->hyphenize($data['display_tag']);
				$check_unique = $this->check_query(PRODUCT_DISPLAY_TAG,"id","token='".$token."' AND delete_status='0' ");
				$check_trash_unique = $this->check_query(PRODUCT_DISPLAY_TAG,"id","token='".$token."' AND delete_status='1' ");
				if($check_unique==0) {
					if($check_trash_unique==0) {
						$admin_id 		= $_SESSION["ecom_admin_id"];
						$curr 			= date("Y-m-d H:i:s");
						$query 			= "INSERT INTO ".PRODUCT_DISPLAY_TAG." SET 
							token 			= '".$token."',
							display_tag 	= '".$data['display_tag']."',
							status			= '1',
							added_by		= '$admin_id',	
							created_at 		= '$curr',
							updated_at 		= '$curr' ";
						$exe = $this->exeQuery($query);
						if($exe){
							$this->unSetCSRF($data['csrf_key']);
							return 1;
						}else{
							return "Sorry!! Unexpected Error Occurred. Please try again.";
						}
					} else {
						return "Entered Display Tag already exists in trash";
					}
				} else {
					return "Entered Display Tag already exists";
				}	
			}else{
				return $validate_csrf;
			}
		}

		// Change Display Tag 

		function changeDisplayTagMasterStatus($data)
		{
			$data = $this->decryptData($data);
			$info = $this -> getDetails(PRODUCT_DISPLAY_TAG,"status"," id ='$data' ");
			if($info['status'] ==1){
				$query = "UPDATE ".PRODUCT_DISPLAY_TAG." SET status='0' WHERE id='$data' ";
			}else{
				$query = "UPDATE ".PRODUCT_DISPLAY_TAG." SET status='1' WHERE id='$data' ";
			}
			$up_exe = $this->exeQuery($query);
			//return $up_exe;
			if($up_exe){
				return 1;
			}
		}

		// Get Display Tag  Details

		function getDisplayTagDetails($data)
		{	
			$layout = "";
			$id     = $this->decryptData($data);
			$query  = "SELECT * FROM  ".PRODUCT_DISPLAY_TAG."  WHERE id='$id' ";
			$exe 	= $this->exeQuery($query);

			if(mysqli_num_rows($exe) > 0) {
				while ($list = mysqli_fetch_assoc($exe)) {
					$layout .= "<input type='hidden' name='token' value='".$this->encryptData($list['id'])."' id='token'>
			                    <div class='form-group'>
			                        <label class='form-label'>Display Tag
			                            <en>*</en>
			                        </label>
			                        <input type='text' name='display_tag' id='display_tag'  value='".$list['display_tag']."' class='form-control' placeholder='Enter Unit Classes Name' required>
			                    </div>
				                <div class='form-error'>
				                </div>
				                <div class='form-group'>
				                    <p class='float-right model_pt'>
				                    <button type='button' class='btn btn-light close_modal' data-modal_id='editDisplayTagModal'>Cancel</button>
				                    <button type='submit' class='btn btn-primary'>Submit</button>
				                    </p>
				                </div>";
				}
			}

			return $layout;
		}

		// Edit Display Tag 

		function editDisplayTag($data)
		{	
			$validate_csrf = $this->validateCSRF($data);
			if($validate_csrf=='success'){
				$token = $this->hyphenize($data['display_tag']);
				$check_unique = $this->check_query(PRODUCT_DISPLAY_TAG,"id","token='".$token."' AND delete_status='0' AND id!='".$this->decryptData($data['token'])."' ");
				$check_trash_unique = $this->check_query(PRODUCT_DISPLAY_TAG,"id","token='".$token."' AND delete_status='1' AND id!='".$this->decryptData($data['token'])."' ");
				if($check_unique==0) {
					if($check_trash_unique==0) {
						$admin_id 		= $_SESSION["ecom_admin_id"];
						$id 		    = $this->decryptData($data["token"]);
						$curr 			= date("Y-m-d H:i:s");
						$query 			= "UPDATE ".PRODUCT_DISPLAY_TAG." SET 
									token 			= '".$this->hyphenize($data['display_tag'])."',
									display_tag 	= '".$this->cleanString($data['display_tag'])."',
									updated_at 		= '$curr' WHERE id='$id' ";
						$exe 	= $this->exeQuery($query);
						if($exe){
							$this->unSetCSRF($data['csrf_key']);
							return 1;
						}else{
							return "Sorry!! Unexpected Error Occurred. Please try again.";
						}	
					} else {
						return "Entered Display Tag already exists in trash";
					}
				} else {
					return "Entered Display Tag already exists";
				}
			}else{
				return "Sorry Invalid User Entry. Try Re login to the Panel to continue.";
			}
		}

		// Update delete status

		function deleteDisplayTag($data)
		{
			$data = $this->decryptData($data);
			$info = $this -> getDetails(PRODUCT_DISPLAY_TAG,"delete_status"," id ='$data' ");
			if($info['delete_status'] ==1){
				$query = "UPDATE ".PRODUCT_DISPLAY_TAG." SET delete_status='0' WHERE id='$data' ";
			}else{
				$query = "UPDATE ".PRODUCT_DISPLAY_TAG." SET delete_status='1' WHERE id='$data' ";
			}
			$up_exe = $this->exeQuery($query);
			if($up_exe){
				return 1;
			}
		}

		// Restore Display Tag 

		function restoreDisplayTag($data)
		{	
			$data = $this->decryptData($data);
			$query = "UPDATE ".PRODUCT_DISPLAY_TAG." SET delete_status='0' WHERE id='$data' ";
			$up_exe = $this->exeQuery($query);
			if($up_exe) {
				return 1;
			}
		}

		/*--------------------------------------------- 
			   Pagination Master
		----------------------------------------------*/ 

		// Manage Pagination

		function managePagination()
 	  	{
 	  		$layout = "";
	    	$q 	 = "SELECT * FROM ".PAGINATION_TBL." WHERE delete_status='0'  ORDER BY id ASC" ;
 		    $exe = $this->exeQuery($q);
 		    if(mysqli_num_rows($exe)>0){
 		    	$i=1;
 		    	while($list = mysqli_fetch_array($exe)){
		    		$status 	  = (($list['status']==1) ? "Active" : "Inactive"); 
		    		$status_c 	  = (($list['status']==1) ? "checked" : " ");
					$status_class = (($list['status']==1) ? "text-success" : "text-danger");
					$td_data 	  = " data-formclass='edit_product_request_status'  data-form='editPagination' data-option='".$this->encryptData($list['id'])."' ";
		    		$layout .= "
		    				<tr class='nk-tb-item'>
		    					<td class='nk-tb-col openEditPagination' $td_data>
	                                ".$i."
	                            </td>
	                            <td class='nk-tb-col openEditPagination' $td_data>
	                                ".$list['id']."
	                            </td>
	                            <td class='nk-tb-col tb-col-md openEditPagination' $td_data>
	                                <span class='text-primary'>".$this->publishContent($list['pagination_count'])."</span>
 	                            </td>
 	                            <td class='nk-tb-col tb-col-md openEditPagination' $td_data>
	                                <span class='text-primary'>".$this->publishContent($list['page_name'])."</span>
 	                            </td>
 	                            <td class='nk-tb-col tb-col-md'>
		                            <div class='custom-control custom-switch'>
	                                    <input type='checkbox' class='custom-control-input changePaginationStatus' data-option='".$this->encryptData($list['id'])."' value='0' id='status_".$i."' save_as_draft' $status_c>
	                                    <label class='custom-control-label ' for='status_".$i."'><span class='$status_class'>$status </span></label>
	                                </div>
	                        	</td>
 	                            <td class='nk-tb-col nk-tb-col-tools'>
	                                <ul class='nk-tb-actions gx-1'>
	                                    <li class='nk-tb-action-hidden'>
	                                        <button class='btn btn-trigger btn-icon deletePagination' data-option='".$this->encryptData($list['id'])."' ><em class='icon ni ni-trash-fill'></em></button>
	                                    </li>
	                                </ul>
                        	    </td>
	                        </tr>";
		    		$i++;
		    	}
 		    }
 		    return $layout;
 		}

		// Add Pagination 

		function addPagination($input)
		{
			$data 		   = $this->cleanStringData($input);
			$validate_csrf = $this->validateCSRF($data);
			if ($validate_csrf=="success") {
				$token 	   = $this->hyphenize($data['page_name']);
				$admin_id  = $_SESSION["ecom_admin_id"];
				$curr 	   = date("Y-m-d H:i:s");
				$query 	   = "INSERT INTO ".PAGINATION_TBL." SET 
					pagination_count = '".$data['pagination_count']."',
					page_name 		 = '".$data['page_name']."',
					status			 = '1',
					added_by		 = '$admin_id',	
					created_at 		 = '$curr',
					updated_at 		 = '$curr' ";
				$exe = $this->exeQuery($query);
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

		// Change Pagination Status

		function changePaginationStatus($data)
		{
			$data = $this->decryptData($data);
			$info = $this -> getDetails(PAGINATION_TBL,"status"," id ='$data' ");
			if($info['status'] ==1){
				$query = "UPDATE ".PAGINATION_TBL." SET status='0' WHERE id='$data' ";
			}else{
				$query = "UPDATE ".PAGINATION_TBL." SET status='1' WHERE id='$data' ";
			}
			$up_exe = $this->exeQuery($query);
			//return $up_exe;
			if($up_exe){
				return 1;
			}
		}

		// Get Pagination Details

		function getPaginationDetails($data)
		{	
			$layout = "";
			$id     = $this->decryptData($data);
			$query  = "SELECT * FROM  ".PAGINATION_TBL."  WHERE id='$id' ";
			$exe 	= $this->exeQuery($query);

			if(mysqli_num_rows($exe) > 0) {
				while($list = mysqli_fetch_assoc($exe)) {
					$layout .= "<input type='hidden' name='token' value='".$this->encryptData($list['id'])."' id='token'>
				                <div class='form-group'>
				                    <label class='form-label'>Pagination
				                        <en>*</en>
				                    </label>
				                    <input type='text' name='pagination_count' id='pagination_count' value='".$list['pagination_count']."'  class='form-control' placeholder='Enter Pagination Count' required>
				                </div>
				                 <div class='form-group'>
				                    <label class='form-label'>Page Name
				                        <en>*</en>
				                    </label>
				                    <input type='text' name='page_name' id='page_name' value='".$list['page_name']."'  class='form-control' placeholder='Enter Page Name' required>
				                </div>
				                <div class='form-error'>
				                </div>
				                <div class='form-group'>
				                    <p class='float-right model_pt'>
				                    <button type='button' class='btn btn-light close_modal' data-modal_id='editPaginationModal'>Cancel</button>
				                    <button type='submit' class='btn btn-primary'>Submit</button>
				                    </p>
				                </div>";
				}
			}

			return $layout;
		}

		// Edit Pagination 

		function editPagination($data)
		{
			if(isset($_SESSION[$data['csrf_key']])){
				if($this->cleanString($data['csrf_token']) == $_SESSION[$data['csrf_key']]){
					$admin_id = $_SESSION["ecom_admin_id"];
					$id 	  = $this->decryptData($data["token"]);
					$curr 	  = date("Y-m-d H:i:s");
					$query 	  = "UPDATE ".PAGINATION_TBL." SET 
								pagination_count = '".$data['pagination_count']."',
								page_name 		 = '".$data['page_name']."',
								updated_at 		 = '$curr' WHERE id='$id' ";
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

		// Update delete status

		function deletePagination($data)
		{
			$data = $this->decryptData($data);
			$info = $this -> getDetails(PAGINATION_TBL,"delete_status"," id ='$data' ");
			if($info['delete_status'] ==1){
				$query = "UPDATE ".PAGINATION_TBL." SET delete_status='0' WHERE id='$data' ";
			}else{
				$query = "UPDATE ".PAGINATION_TBL." SET delete_status='1' WHERE id='$data' ";
			}
			$up_exe = $this->exeQuery($query);
			if($up_exe){
				return 1;
			}
		}

		// Restore Pagination 

		function restorePagination($data)
		{	
			$data = $this->decryptData($data);
			$query = "UPDATE ".PAGINATION_TBL." SET delete_status='0' WHERE id='$data' ";
			$up_exe = $this->exeQuery($query);
			if($up_exe) {
				return 1;
			}
		}

		/*--------------------------------------------- 
					Filter Group Settings
		----------------------------------------------*/ 	

		function manageFilterGroup()
 	  	{
 	  		$layout = "";
	    	$q 	 = "SELECT * FROM ".FILTER_GROUP_MASTER_TBL." WHERE 1  ORDER BY id ASC" ;
 		    $exe = $this->exeQuery($q);
 		    if(mysqli_num_rows($exe)>0){
 		    	$i=1;
 		    	while($list = mysqli_fetch_array($exe)){
		    		$status 	  = (($list['status']==1) ? "Active" : "Inactive"); 
		    		$status_c 	  = (($list['status']==1) ? "checked" : " ");
					$status_class = (($list['status']==1) ? "text-success" : "text-danger");
					$td_data      = " data-formclass='edit_return_reason' data-form='editFilterGroup' data-option='".$this->encryptData($list['id'])."' ";
		    		$layout .= "
		    				<tr class='nk-tb-item'>
		    					<td class='nk-tb-col openEditFilterGroup' $td_data>
	                                ".$i."
	                            </td>
	                            <td class='nk-tb-col openEditFilterGroup' $td_data>
	                                ".$list['id']."
	                            </td>
	                            <td class='nk-tb-col tb-col-md openEditFilterGroup' $td_data>
	                                <span class='text-primary'>".$this->publishContent($list['filter_group_name'])."</span>
 	                            </td>
 	                            <td class='nk-tb-col tb-col-md'>
		                            <div class='custom-control custom-switch'>
	                                    <input type='checkbox' class='custom-control-input changeFilterGroupStatus' data-option='".$this->encryptData($list['id'])."' value='0' id='status_".$i."' save_as_draft' $status_c>
	                                    <label class='custom-control-label ' for='status_".$i."'><span class='$status_class'>$status </span></label>
	                                </div>
	                        	</td>
 	                           
	                        </tr>";
		    		$i++;

					// <td class='nk-tb-col nk-tb-col-tools'>
					// 	<ul class='nk-tb-actions gx-1'>
					// 		<li class='nk-tb-action-hidden'>
					// 			<button class='btn btn-trigger btn-icon deleteFilterGroup' data-option='".$this->encryptData($list['id'])."' ><em class='icon ni ni-trash-fill'></em></button>
					// 		</li>
					// 	</ul>
					// </td>
		    	}
 		    }
 		    return $layout;
 		}
		
		// Add Filter Group 

		function addFilterGroup($input)
		{	
			$data 		   = $this->cleanStringData($input);
			$validate_csrf = $this->validateCSRF($data);
			if ($validate_csrf=="success") {
				$token = $this->hyphenize($data['filter_group_name']);

				if($token == "url"){
					$token = $token.$this->generateRandomString("5");
				} 

				$check_unique = $this->check_query(FILTER_GROUP_MASTER_TBL,"id","token='".$token."' ");
				if ($check_unique==0) {
					$admin_id 		= $_SESSION["ecom_admin_id"];
					$curr 			= date("Y-m-d H:i:s");
					$query 			= "INSERT INTO ".FILTER_GROUP_MASTER_TBL." SET 
								token 			  = '".$token."',
								filter_group_name = '".$data['filter_group_name']."',
								status			  ='1',
								created_at 		  = '$curr',
								updated_at 		  = '$curr' ";
					$exe = $this->exeQuery($query);
					if($exe){
						$this->unSetCSRF($data['csrf_key']);
						return 1;
					}else{
						return "Sorry!! Unexpected Error Occurred. Please try again.";
					}
				} else {
					return "Entered filter group name already exists";
				}	
			}else{
				return $validate_csrf;
			}
		}

		// Change Filter Group Status

		function changeFilterGroupStatus($data)
		{
			$data = $this->decryptData($data);
			$info = $this -> getDetails(FILTER_GROUP_MASTER_TBL,"status"," id ='$data' ");
			if($info['status'] ==1){
				$query = "UPDATE ".FILTER_GROUP_MASTER_TBL." SET status='0' WHERE id='$data' ";
			}else{
				$query = "UPDATE ".FILTER_GROUP_MASTER_TBL." SET status='1' WHERE id='$data' ";
			}
			$up_exe = $this->exeQuery($query);
			//return $up_exe;
			if($up_exe){
				return 1;
			}
		}

		// Get Filter Group  Details

		function getFilterGroupDetails($data)
		{		
			$layout = "";
			$id     = $this->decryptData($data);
			$query  = "SELECT * FROM  ".FILTER_GROUP_MASTER_TBL."  WHERE id='$id' ";
			$exe    = $this->exeQuery($query);
	        $list   = $this->editPagePublish(mysqli_fetch_array($exe));
	        return json_encode($list);   
		}

		// Edit Filter Group 

		function editFilterGroup($data)
		{	
			if(isset($_SESSION[$data['csrf_key']])){
				if($this->cleanString($data['csrf_token']) == $_SESSION[$data['csrf_key']]){
					
					$admin_id 	= $_SESSION["ecom_admin_id"];
					$id 		= $data["token"];
					$curr 		= date("Y-m-d H:i:s");

					$token = $this->hyphenize($data['filter_group_name']);
					$check_unique = $this->check_query(FILTER_GROUP_MASTER_TBL,"id","token='".$token."' AND id!='".$id."' ");
					if ($check_unique==0) {
						$query 		= "UPDATE ".FILTER_GROUP_MASTER_TBL." SET 
									token 			  = '".$token."',
									filter_group_name = '".$this->cleanString($data['filter_group_name'])."',
									updated_at 		  = '$curr' WHERE id='$id' ";
						$exe 	= $this->exeQuery($query);
						if($exe){
							$this->unSetCSRF($data['csrf_key']);
							return 1;
						} else {
							return "Sorry!! Unexpected Error Occurred. Please try again.";
						}
						
					} else {
						return "Entered filter group name already exists";
					}	
				}else{
					return "Sorry Invalid User Entry. Try Re login to the Panel to continue.";
				}
			} else {  return "Sorry Invalid User Entry. Try Re login to the Panel to continue.";
			}
		}

		// Update delete status

		function deleteFilterGroup($data)
		{
			$data = $this->decryptData($data);
			$info = $this -> getDetails(FILTER_GROUP_MASTER_TBL,"delete_status"," id ='$data' ");
			if($info['delete_status'] ==1){
				$query = "UPDATE ".FILTER_GROUP_MASTER_TBL." SET delete_status='0' WHERE id='$data' ";
			}else{
				$query = "UPDATE ".FILTER_GROUP_MASTER_TBL." SET delete_status='1' WHERE id='$data' ";
			}
			$up_exe = $this->exeQuery($query);
			if($up_exe){
				return 1;
			}
		}

		// Restore Filter Group 

		function restoreFilterGroup($data)
		{	
			$data = $this->decryptData($data);
			$query = "UPDATE ".FILTER_GROUP_MASTER_TBL." SET delete_status='0' WHERE id='$data' ";
			$up_exe = $this->exeQuery($query);
			if($up_exe) {
				return 1;
			}
		}

		/*--------------------------------------------- 
					Filter Settings
		----------------------------------------------*/ 	

		function manageFilter()
 	  	{
 	  		$layout = "";
	    	$q 	 	= "SELECT * FROM ".FILTER_MASTER_TBL." WHERE 1  ORDER BY id ASC" ;
 		    $exe 	= $this->exeQuery($q);
 		    if(mysqli_num_rows($exe)>0){
 		    	$i=1;
 		    	while($list = mysqli_fetch_array($exe)){
		    		$status 	  = (($list['status']==1) ? "Active" : "Inactive"); 
		    		$status_c 	  = (($list['status']==1) ? "checked" : " ");
					$status_class = (($list['status']==1) ? "text-success" : "text-danger");
					$td_data      = " data-formclass='edit_return_reason' data-form='editFilter' data-option='".$this->encryptData($list['id'])."' ";
					$filter_group = $this->getDetails(FILTER_GROUP_MASTER_TBL,"filter_group_name","id='".$list['filter_group_id']."'");
		    		$layout .= "
		    				<tr class='nk-tb-item'>
		    					<td class='nk-tb-col openEditFilter' $td_data>
	                                ".$i."
	                            </td>
	                            <td class='nk-tb-col openEditFilter' $td_data>
	                                ".$list['id']."
	                            </td>
	                            <td class='nk-tb-col tb-col-md openEditFilter' $td_data>
	                                <span class='text-primary'>".$this->publishContent($list['filter_value'])."</span>
 	                            </td>
 	                            <td class='nk-tb-col tb-col-md openEditFilter' $td_data>
	                                <span class='text-primary'>".$this->publishContent($filter_group['filter_group_name'])."</span>
 	                            </td>
 	                            <td class='nk-tb-col tb-col-md'>
		                            <div class='custom-control custom-switch'>
	                                    <input type='checkbox' class='custom-control-input changeFilterStatus' data-option='".$this->encryptData($list['id'])."' value='0' id='status_".$i."' save_as_draft' $status_c>
	                                    <label class='custom-control-label ' for='status_".$i."'>
	                                    	<span class='$status_class'>$status </span>
	                                    </label>
	                                </div>
	                        	</td>   
	                        </tr>";
		    		$i++;

					// <td class='nk-tb-col nk-tb-col-tools'>
					// 	<ul class='nk-tb-actions gx-1'>
					// 		<li class='nk-tb-action-hidden'>
					// 			<button class='btn btn-trigger btn-icon deleteFilter' data-option='".$this->encryptData($list['id'])."' ><em class='icon ni ni-trash-fill'></em></button>
					// 		</li>
					// 	</ul>
					// </td>
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
		
		// Add Filter 

		function addFilter($input)
		{	
			$data 		   = $this->cleanStringData($input);
			$validate_csrf = $this->validateCSRF($data);
			if ($validate_csrf=="success") {

				$token = $this->hyphenize($data['filter_value']);

				if($token == "url"){
					$token = $token.$this->generateRandomString("5");
				} 

				$check_unique = $this->check_query(FILTER_MASTER_TBL,"id","token='".$token."' AND filter_group_id='".$data['filter_group_id']."' ");

				if ($check_unique==0) {
					$admin_id 		= $_SESSION["ecom_admin_id"];
					$curr 			= date("Y-m-d H:i:s");
					$query 			= "INSERT INTO ".FILTER_MASTER_TBL." SET 
								token 			  = '".$token."',
								filter_value 	  = '".$data['filter_value']."',
								filter_group_id   = '".$data['filter_group_id']."',
								status			  ='1',
								created_at 		  = '$curr',
								updated_at 		  = '$curr' ";
					$exe = $this->exeQuery($query);
					if($exe){
						$this->unSetCSRF($data['csrf_key']);
						return 1;
					}else{
						return "Sorry!! Unexpected Error Occurred. Please try again.";
					}
				} else {
					return "Entered filter name already exists in same filter group";
				}	
			}else{
				return $validate_csrf;
			}
		}

		// Change Filter Status

		function changeFilterStatus($data)
		{
			$data = $this->decryptData($data);
			$info = $this -> getDetails(FILTER_MASTER_TBL,"status"," id ='$data' ");
			if($info['status'] ==1){
				$query = "UPDATE ".FILTER_MASTER_TBL." SET status='0' WHERE id='$data' ";
			}else{
				$query = "UPDATE ".FILTER_MASTER_TBL." SET status='1' WHERE id='$data' ";
			}
			$up_exe = $this->exeQuery($query);
			//return $up_exe;
			if($up_exe){
				return 1;
			}
		}

		// Get Filter  Details

		function getFilterDetails($data)
		{		
			$layout = "";
			$id     = $this->decryptData($data);
			$query  = "SELECT * FROM  ".FILTER_MASTER_TBL."  WHERE id='$id' ";
			$exe    = $this->exeQuery($query);
	        $list   = $this->editPagePublish(mysqli_fetch_array($exe));

	        $layout = "<input type='hidden' name='token' id='edit_token' value='".$list['id']."'>
                            <div class='form-group'>
                                <label class='form-label'>Filter 
                                    <en>*</en>
                                </label>
                                <input type='text' name='filter_value' id='filter_name_edit' value='".$list['filter_value']."' class='form-control' placeholder='Enter Filter Name' required>
                            </div>
                            <div class='form-group'>
                                <label class='form-label'>Select Filter Group <em>*</em></label>
                                <div class='form-control-wrap'>
                                    <select class='form-select filter_group_select' id='filter_group_id_edit' name='filter_group_id' data-search='on' required>
                                    	".$this->getFilterGroupsDropDown($list['filter_group_id'])."
                                    </select>
                                </div>
                            </div>
                            <div class='form-group'>
                                <p class='float-right model_pt'>
                                    <button type='button' class='btn btn-light close_modal' data-modal_id='editFilterModal'>Cancel</button>
                                    <button type='submit' class='btn btn-primary'>Update</button>
                                </p>
                            </div>";   
			return $layout;                           
		}

		// Edit Filter 

		function editFilter($data)
		{	
			if(isset($_SESSION[$data['csrf_key']])){
				if($this->cleanString($data['csrf_token']) == $_SESSION[$data['csrf_key']]){
					
					$admin_id 	= $_SESSION["ecom_admin_id"];
					$id 		= $data["token"];
					$curr 		= date("Y-m-d H:i:s");

					$token = $this->hyphenize($data['filter_value']);
					$check_unique = $this->check_query(FILTER_MASTER_TBL,"id","token='".$token."' AND filter_group_id='".$data['filter_group_id']."' AND id!='".$id."' ");
					if ($check_unique==0) {
						$query 		= "UPDATE ".FILTER_MASTER_TBL." SET 
									token 			  = '".$token."',
									filter_value 	  = '".$this->cleanString($data['filter_value'])."',
									filter_group_id   = '".$this->cleanString($data['filter_group_id'])."',
									updated_at 		  = '$curr' WHERE id='$id' ";
						$exe 	= $this->exeQuery($query);
						if($exe){
							$this->unSetCSRF($data['csrf_key']);
							return 1;
						} else {
							return "Sorry!! Unexpected Error Occurred. Please try again.";
						}
						
					} else {
						return "Entered filter name already exists in same filter group";
					}	
				}else{
					return "Sorry Invalid User Entry. Try Re login to the Panel to continue.";
				}
			} else {  return "Sorry Invalid User Entry. Try Re login to the Panel to continue.";
			}
		}

		// Update delete status

		function deleteFilter($data)
		{
			$data = $this->decryptData($data);
			$info = $this -> getDetails(FILTER_MASTER_TBL,"delete_status"," id ='$data' ");
			if($info['delete_status'] ==1){
				$query = "UPDATE ".FILTER_MASTER_TBL." SET delete_status='0' WHERE id='$data' ";
			}else{
				$query = "UPDATE ".FILTER_MASTER_TBL." SET delete_status='1' WHERE id='$data' ";
			}
			$up_exe = $this->exeQuery($query);
			if($up_exe){
				return 1;
			}
		}

		// Restore Filter 

		function restoreFilter($data)
		{	
			$data = $this->decryptData($data);
			$query = "UPDATE ".FILTER_MASTER_TBL." SET delete_status='0' WHERE id='$data' ";
			$up_exe = $this->exeQuery($query);
			if($up_exe) {
				return 1;
			}
		}

	/*-----------Dont'delete---------*/

	}


?>




