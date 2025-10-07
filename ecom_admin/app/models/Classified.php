<?php
	require_once 'Model.php';
	require_once 'FileUploader.php';
	require_once 'MultipleFileUploader.php';
	require_once 'config/config.php';
	require_once 'app/core/classes/PHPMailerAutoload.php';

	class Classified extends Model
	{	

		/*--------------------------------------------- 
					  Classified Management
		----------------------------------------------*/

		// Manage Classified 

		function manageClassified() 
		{
			$layout = "";
	    	$q = "SELECT * FROM ".CONTRACTOR_TBL." WHERE 1 AND delete_status='0' ORDER BY id ASC" ;
 		    $exe = $this->exeQuery($q);
 		    if(mysqli_num_rows($exe)>0){
 		    	$i=1;
 		    	while($rows = mysqli_fetch_array($exe)){

		    		$list 	      = $this->editPagePublish($rows);
		    		$status       = (($list['status']==1) ? "On" : "Off"); 
		    		$status_c     = (($list['status']==1) ? "checked" : " ");
					$status_class = (($list['status']==1) ? "text-success" : "text-danger");

					// Draft Status
					$draft_status        = (($list['is_draft']==1) ? "Draft" : "Published"); 
					$draft_status_c      = (($list['is_draft']==1) ? "checked" : ""); 
					$draft_status_class  = (($list['is_draft']==1) ? "text-warning" : "text-success"); 
					$is_draft_row        = (($list['is_draft']==1) ? "draft_item" : "");
					$invite_button       = (($list['invite_status']==1) ? "Resend" : "Send");
					$invite_button_class = (($list['invite_status']==0) ? "btn-info" : "btn-warning"); 
					$invite_disable      = (($list['is_draft']==1) ? "disabled" : "");  


					//profile verified
					$profile_verified       = (($list['profile_verified']==1)? 1 : 0 );
					$profile_verified_text  = (($profile_verified==1) ? "Verified" : "Not Verified"); 
					$profile_verified_class = (($profile_verified==0) ? "text-danger" : "text-success"); 

					if ($list['is_draft']==1) {
						$draft_action       = "<button type='button' data-option='".$this->encryptData($list['id'])."' class='btn btn-warning btn-dim btn-sm publishClassified' ><em class='icon ni ni-check-thick'></em> Publish</button>";
						$delete_class       = 'deleteClassified';
						$delete_class_hover = 'Delete Classified';
					} else {
						$draft_action       = "
											<div class='tb-tnx-status'>
												<span class='badge badge-dot text-success cursor_pointer'> Published </span>
											</div>";
	                    $delete_class       = 'trashClassified';
	                    $delete_class_hover = 'Trash Classified';
	                    
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

					$td_data = "data-data_id='".$list['token']."' data-data_link='hire/details' ";

		    		$layout .= "
	    				<tr class='nk-tb-item $is_draft_row'>
	    					<td class='nk-tb-col td_click' $td_data>
                                ".$i."
                            </td>
                            <td class='nk-tb-col tb-col-md td_click' $td_data>
                                <a href='".COREPATH."hire/details/".$list['token']."' ><span class='text-primary'>".$this->publishContent($list['name'])."</span></a>
	                        </td>
	                        <td class='nk-tb-col tb-col-md td_click' $td_data>
                                <span >".$this->publishContent($list['company_name'])."</span>
	                        </td>
	                        <td class='nk-tb-col tb-col-md td_click' $td_data>
                                <em class='icon ni ni-mail'></em> <span >". $list['email']."</span><br>
                                <em class='icon ni ni-mobile'></em> <span >". $list['mobile']."</span>
	                        </td>
	                        <td class='nk-tb-col td_click' $td_data>
                                ".$list['city']."
                            </td>
                            <td class='nk-tb-col td_click' $td_data>
                                <div class='tb-tnx-status'>
	                            	<span class='badge badge-dot ".$profile_verified_class." cursor_pointer' > ".$profile_verified_text." </span>
	                            </div>
                            </td>
	                        <td class='nk-tb-col td_click' $td_data>
                               	$draft_action
                            </td>
	                        <td class='nk-tb-col tb-col-md'>
	                            <div class='custom-control custom-switch custom-control-sm'>
                                    <input type='checkbox' class='custom-control-input changeClassifiedStatus' data-option='".$this->encryptData($list['id'])."'   value='0'  id='status_".$i."' name='save_as_draft' $status_c>
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

		// Get Service Taag to map 

		function getServiceTag($current="")
	  	{	

	  		if($current=="") {
	  			$current =  array();
	  		} else {
	  			$service_tags = explode(",",$current);
	  			$current      = $service_tags;
	  		}

	  		$layout = "";
	  		$q      = "SELECT id,token,service_tag FROM ".SERVICE_TAGS_TBL." WHERE status='1' AND delete_status='0' " ;
	  		$query  = $this->exeQuery($q);

		    if(@mysqli_num_rows($query)>0){
		    	$i=0;
		    	while($rows   = mysqli_fetch_array($query)){
		    		$list 	  = $this->editPagePublish($rows);
					$selected = ((in_array($list['id'],$current)) ? 'selected' : '');
					$layout  .= "<option value='".$list['id']."' $selected>".$list['service_tag']."</option>";
	                $i++;
		    	}
		    }
		    return $layout;
	  	}

	  	// Get Classified Profile List

		function getClassifiedProfiles($current="")
	  	{	

	  		if($current=="") {
	  			$current =  array();
	  		} else {
	  			$profile = explode(",",$current);
	  			$current = $profile;
	  		}
	  		$layout = "";
	  		$q 		= "SELECT * FROM ".CONTRACTOR_PROFILE_TBL." WHERE status='1' AND delete_status='0' " ;
	  		$query = $this->exeQuery($q);
		    if(@mysqli_num_rows($query)>0){
		    	$i=0;
		    	while($list = mysqli_fetch_array($query)){
					$selected = ((in_array($list['id'],$current)) ? 'checked' : '');
					$layout.= "<div class='form-group col-md-2'>
									<div class='custom-control custom-control-sm custom-checkbox'>
									    <input type='checkbox' class='custom-control-input' value='".$list['id']."' name='profile[]' id='customCheck".$i."' $selected>
									    <label class='custom-control-label' for='customCheck".$i."'> <label class='form-label'>".$list['profile']."
									    </label></label>
									 </div>
								</div>";
	                $i++;
		    	}
		    }
		    return $layout;
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

		// Add Classified 

		function addClassified($input,$image)
		{	

			$validate_csrf = $this->validateCSRF($input);
			if($validate_csrf=='success') {
				$name = $this->hyphenize($input['name']);
				$check_token = $this->check_query(CONTRACTOR_TBL,"id"," token='".$name."' ");
				if ($check_token==0) {
					$token = $name;
				}else{
					$token = $name."-".$this->generateRandomString("5");
				}

				$service_tags = "";
				if (isset($input['service_tags'])) {
					$ServiceTags = $input['service_tags'];
					$count_group = count($ServiceTags);
					if ($count_group>0) {
						for ($i=0; $i < $count_group; $i++) {
							$comma = (($i>0) ? "," : "");
							$service_tags .= $comma.$ServiceTags[$i];
						}
					}
				}

				$profile = "";
				if (isset($input['profile'])) {
					$profiles = $input['profile'];
					$count_group = count($profiles);
					if ($count_group>0) {
						for ($i=0; $i < $count_group; $i++) {
							$comma = (($i>0) ? "," : "");
							$profile .= $comma.$profiles[$i];
						}
					}
				}

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
				$admin_id 		= $_SESSION["ecom_admin_id"];
				$curr 			= date("Y-m-d H:i:s");
				$save_as_draft  = isset($input['save_as_draft']) ? 1 : 0;
				$state_info     = $this->getDetails(STATE_TBL,"name","id='".$input['state_id']."'");

				if($input['experience'] > 1) {
					if($input['experience_duration']=="month"){
						$experience_duration = "Months";
					} else {
						$experience_duration = "Years";
					}
				} else {
					if($input['experience_duration']=="month"){
						$experience_duration = "Month";
					} else {
						$experience_duration = "Year";
					}
				}

				$check_email = $this -> check_query(CONTRACTOR_TBL,"id","email='".$input['email']."' ");
				if($check_email==0) {
					
					$check_mobile = $this -> check_query(CONTRACTOR_TBL,"id","mobile='".$input['mobile']."' ");
					if($check_mobile==0) {
						if($profile!="") {
							if($file_status=="ok") {
									$query = "INSERT INTO ".CONTRACTOR_TBL." SET 
											token 					= '".$token."',
											name					= '".$this->cleanString($input['name'])."',
											company_name			= '".$this->cleanString($input['title_name'])."',
											mobile 					= '".$this->cleanString($input['mobile'])."',
											email 					= '".$this->cleanString($input['email'])."',
											profile_type 			= '".$profile."',
											phone 					= '".$this->cleanString($input['whatsapp'])."',
											whatsapp 				= '".$this->cleanString($input['whatsapp'])."',
											address 				= '".$this->cleanString($input['address'])."',
											city 					= '".$this->cleanString($input['city'])."',
											pincode 				= '".$this->cleanString($input['pincode'])."',
											gst_no 					= '".$this->cleanString($input['gst_no'])."',
											state_id 				= '".$this->cleanString($input['state_id'])."',
											experience 				= '".$this->cleanString($input['experience'])."',
											experience_duration     = '".$experience_duration."',
											state_name 				= '".$state_info['name']."',
											service_tags 			= '".$service_tags."',
											description 			= '".$this->cleanString($input['description'])."'
											".$image_q."
											,is_draft 				= '".$save_as_draft."',
											status					= '1',
											added_by 				= '$admin_id',	
											created_at 				= '$curr',
											updated_at 				= '$curr' ";
											// var_dump($query);

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
							return "Select at least one profile type for the user";
						}
					} else {
						return "Entered mobile number is already registered in another Expert profile.";
					}
				} else {
					return "Entered email address is already registered in another Expert profile.";
				}
						
			}else{
				return $validate_csrf;
			}
		}

		// Edit Classified 

		function editClassified($input,$image)
		{ 	
			
			$validate_csrf = $this->validateCSRF($input);
			if($validate_csrf=='success'){
				$id =	$this->decryptData($input['session_token']);

				if (isset($input['save_as_draft'])==1) {
					$name = $this->hyphenize($input['name']);
					$check_token = $this->check_query(CONTRACTOR_TBL,"id"," token='".$name."' AND id!='$id' ");
					if ($check_token==0) {
						$token = $name;
					}else{
						$token = $name."-".$this->generateRandomString("5");
					}
					$token_q = "token  = '".$token."', ";
				}else{
					$token_q = '';
				}

				$service_tags = "";
				if (isset($input['service_tags'])) {
					$ServiceTags = $input['service_tags'];
					$count_group = count($ServiceTags);
					if ($count_group>0) {
						for ($i=0; $i < $count_group; $i++) {
							$comma = (($i>0) ? "," : "");
							$service_tags .= $comma.$ServiceTags[$i];
						}
					}
				}

				$profile = "";
				if (isset($input['profile'])) {
					$profiles = $input['profile'];
					$count_group = count($profiles);
					if ($count_group>0) {
						for ($i=0; $i < $count_group; $i++) {
							$comma = (($i>0) ? "," : "");
							$profile .= $comma.$profiles[$i];
						}
					}
				}

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

				if($input['experience'] > 1) {
					if($input['experience_duration']=="month"){
						$experience_duration = "Months";
					} else {
						$experience_duration = "Years";
					}
				} else {
					if($input['experience_duration']=="month"){
						$experience_duration = "Month";
					} else {
						$experience_duration = "Year";
					}
				}
			
				$admin_id 		= $_SESSION["ecom_admin_id"];
				$curr 			= date("Y-m-d H:i:s");
				$save_as_draft 	= isset($input['save_as_draft']) ? 1 : 0;
				$state_info     = $this->getDetails(STATE_TBL,"name","id='".$input['state_id']."'");


				$check_mobile = $this -> check_query(CONTRACTOR_TBL,"id","mobile='".$input['mobile']."' AND id!='$id' ");
				if($check_mobile==0) {
					$check_email = $this -> check_query(CONTRACTOR_TBL,"id","email='".$input['email']."' AND id!='$id' ");
					if($check_email==0) {
						if($profile!="") {
							if ($file_status=="ok") {
							 	$query = "UPDATE ".CONTRACTOR_TBL." SET 
									".$token_q."
									name					= '".$this->cleanString($input['name'])."',
									company_name			= '".$this->cleanString($input['title_name'])."',
									mobile 					= '".$this->cleanString($input['mobile'])."',
									email 					= '".$this->cleanString($input['email'])."',
									profile_type 			= '".$profile."',
									phone 					= '".$this->cleanString($input['whatsapp'])."',
									whatsapp 				= '".$this->cleanString($input['whatsapp'])."',
									address 				= '".$this->cleanString($input['address'])."',
									city 					= '".$this->cleanString($input['city'])."',
									pincode 				= '".$this->cleanString($input['pincode'])."',
									gst_no 					= '".$this->cleanString($input['gst_no'])."',
									state_id 				= '".$this->cleanString($input['state_id'])."',
									experience 				= '".$this->cleanString($input['experience'])."',
									experience_duration     = '".$experience_duration."',
									state_name 				= '".$state_info['name']."',
									service_tags 			= '".$service_tags."',
									description 			= '".$this->cleanString($input['description'])."'
									".$image_q."
									,is_draft 				= '".$save_as_draft."',
									status					= '1',
									added_by 				= '$admin_id',	
									updated_at 				= '$curr' 
									WHERE id='$id'";
									// var_dump($query);
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
								return "Select at least one profile type for the user";
						}
					} else {
						return "Entered email address is already registered.";
					}
				} else {
					return "Entered mobile number is already registered.";
				}
					
			}else{
				return "Sorry Invalid User Entry. Try Re login to the Panel to continue.";
			}
		}

		// Send Invie
		function sendInvite($data)
		{
			$data = $this->decryptData($data);
			$info = $this->getDetails(CONTRACTOR_TBL,"*","id ='$data'");

			if($info['invite_status']==0) {
				$passw 		= $this->mobileToken(4);
				$password 	= $this->encryptPassword($passw);
				$password_q = " hash_password 		= '".$password."',
							    password  = '".$passw."', ";
			} else {
				$passw 		= $info['password'];
				$password_q = "";
			}

			$contact_info       = $this->getDetails(COMPANNY_INFO,"*"," id='1' ");
			$curr   			= date("Y-m-d H:i:s");
			$sender_mail 		= NO_REPLY;
			$sender 			= $contact_info['company_name'];
	        $receiver_mail 		= $info['email'];
	        $subject        	= "Login details for"." - ".ucwords($info['name']);
	        $message 			= $this->contractorLoginInfo($info['name'],$info['email'],$passw);
	        $send_mail 			= $this->send_mail($sender_mail,$receiver_mail,$subject,$message);
			if($send_mail){
	            $query  = "UPDATE ".CONTRACTOR_TBL." SET
					invite_status 	= '1',
					".$password_q."
					updated_at 		= '$curr'
					WHERE id 		= '$data' ";
				$exe 	= $this->exeQuery($query);
				return 1;
			}else{
				return "Sorry!! Unexpected Error Occurred. Please try again.";
			}

		}

		// Profile Verified
		function profileVerified($data)
		{
			$data 	= $this->decryptData($data);
			$info 	= $this->getDetails(CONTRACTOR_TBL,"*","id ='$data'");
			$check 	= $this->check_query(CONTRACTOR_TBL,"id"," id='$data' ");

			if($info['profile_verified']==0) {
				$verified_value = "1";
			} else {
				$verified_value = "0";
			}
			$curr   			= date("Y-m-d H:i:s");
			if($check){
	            $query  = "UPDATE ".CONTRACTOR_TBL." SET
					profile_verified 	= '$verified_value',
					updated_at 			= '$curr'
					WHERE id 			= '$data' ";
				$exe 	= $this->exeQuery($query);
				return 1;
			}else{
				return "Sorry!! Unexpected Error Occurred. Please try again.";
			}

		}

		// Change Classified  Status

		function changeClassifiedStatus($data)
		{
			$data = $this->decryptData($data);
			$info = $this -> getDetails(CONTRACTOR_TBL,"status"," id ='$data' ");
			if($info['status'] ==1){
				$query = "UPDATE ".CONTRACTOR_TBL." SET status='0' WHERE id='$data' ";
			}else{
				$query = "UPDATE ".CONTRACTOR_TBL." SET status='1' WHERE id='$data' ";
			}
			$up_exe = $this->exeQuery($query);
			if($up_exe){
				return 1;
			}
		}

		// Update Classified delete status

		function deleteClassified($data)
		{	
			$data = $this->decryptData($data);
			$info = $this -> getDetails(CONTRACTOR_TBL,"file_name"," id ='$data' AND is_draft='1' ");
			// Remove Image
			if ($info['file_name']!='') {
				unlink("./resource/uploads/".$info['file_name']);
			}
			$delete = $this -> deleteRow(CONTRACTOR_TBL," id ='$data' AND is_draft='1' ");
			return $delete;
		}

		// Trash Classified

		function trashClassified($data)
		{
			$data = $this->decryptData($data);
			$query = "UPDATE ".CONTRACTOR_TBL." SET delete_status='1' WHERE id='$data' ";
			$up_exe = $this->exeQuery($query);
			if($up_exe) {
				return 1;
			}
		}

		// Publish Classified

		function publishClassified($data)
		{
			$data = $this->decryptData($data);
			$query = "UPDATE ".CONTRACTOR_TBL." SET is_draft='0' WHERE id='$data' ";
			$up_exe = $this->exeQuery($query);
			if($up_exe) {
				return 1;
			}	
		}

		// Restore Bolg

		function restoreClassified($data)
		{	
			$data = $this->decryptData($data);
			$query = "UPDATE ".CONTRACTOR_TBL." SET delete_status='0' WHERE id='$data' ";
			$up_exe = $this->exeQuery($query);
			if($up_exe) {
				return 1;
			}
		}

		/*----------------------------------------
				Manage Enquiry 
		-----------------------------------------*/

		function manageEnquiry($contractor)
		{
			$layout = "";
			$q = "SELECT * FROM ".CONTRACTOR_ENQUIRY_TBL." WHERE contractor_id='$contractor' AND delete_status='0' AND status='1' ORDER BY id DESC " ;
		    $query = $this->exeQuery($q);	
		    if(mysqli_num_rows($query) > 0){
		    	$i=1;
		    	while($details = mysqli_fetch_array($query)){
		    		$list 		= $this->editPagePublish($details);

		    		$layout .= "
		    				<tr class='nk-tb-item'>
	    					<td class='nk-tb-col'>
                                ".$i."
                            </td>
                             <td class='nk-tb-col'>
                                ".date("d-m-Y", strtotime($list['created_at']))."
                            </td>
                            <td class='nk-tb-col tb-col-md'>
                                <span class='text-primary'>".$this->publishContent($list['name'])."</span>
	                        </td>
	                       	<td class='nk-tb-col nk-tb-col-tools'>
                                ".$list['message']."
                            </td> 
                            <td class='nk-tb-col'>
                                ".$list['email']."
                            </td>
                            <td class='nk-tb-col'>
                                ".$list['mobile']."
                            </td>
                        </tr>";
		    		$i++;
		    	}
		    }
		    	return $layout;
		}

		// Classified Details Viewed List

		function manageDetailsViewedList($contractor)
		{
			$layout = "";
			$q = "SELECT * FROM ".CONTRACTOR_DETAILS_VIEWED_TBL." WHERE contractor_id='$contractor' AND status='1' ORDER BY id DESC " ;
		    $query = $this->exeQuery($q);	
		    if(mysqli_num_rows($query) > 0){
		    	$i=1;
		    	while($details = mysqli_fetch_array($query)){
		    		$list 		= $this->editPagePublish($details);

		    		$layout .= "
		    			<tr class='nk-tb-item'>
	    					<td class='nk-tb-col'>
                                ".$i."
                            </td>
                             <td class='nk-tb-col'>
                                ".date("d-m-Y", strtotime($list['created_at']))."
                            </td>
                            <td class='nk-tb-col tb-col-md'>
                                <span class='text-primary'>".$this->publishContent($list['name'])."</span>
	                        </td>
                            <td class='nk-tb-col'>
                                ".$list['mobile']."
                            </td>
                        </tr>";
		    		$i++;
		    	}
		    }
		    	return $layout;
		}

		// Get Enquiry Details

		function getEnquiryDetails($data){
			$layout = "";
			$enq_id = $this->decryptData($data);
			$info   = $this->getDetails(CONTRACTOR_ENQUIRY_TBL,"*"," id='$enq_id' ");
			$info   = $this->editPagePublish($info);
			$layout .="
						<div class='card card-bordered'>
		                    <ul class='data-list is-compact'>
		                        <li class='data-item'>
		                            <div class='data-col'>
		                                <div class='data-label'>Name</div>
		                                <div class='data-value'>".$info['name']."</div>
		                            </div>
		                        </li>
		                        <li class='data-item'>
		                            <div class='data-col'>
		                                <div class='data-label'>Email</div>
		                                <div class='data-value'> ".$info['email']."</div>
		                            </div>
		                        </li>
		                        <li class='data-item'>
		                            <div class='data-col'>
		                                <div class='data-label'>Mobile</div>
		                                <div class='data-value'>".$info['mobile']."</div>
		                            </div>
		                        </li>
		                        <li class='data-item'>
		                            <div class='data-col'>
		                                <div class='data-label'>Message</div>
		                                <div class='data-value'> ".$info['message']."</div>
		                            </div>
		                        </li>
		                        
		                        <li class='data-item'>
		                            <div class='data-col'>
		                                <div class='data-label'>Date</div>
		                                <div class='data-value'> ".date("d-m-Y", strtotime($info['created_at']))."</div>
		                            </div>
		                        </li>
		                    </ul>
		                </div>
					";
			return $layout;
		}
		
		/*--------------------------------------------- 
					Service Tag Management
		----------------------------------------------*/

		// Manage Service Tag

		function manageServiceTags() 
		{
			$layout = "";
	    	$q = "SELECT * FROM ".SERVICE_TAGS_TBL." WHERE delete_status='0'  ORDER BY id DESC" ;
 		    $exe = $this->exeQuery($q);
 		    if(mysqli_num_rows($exe)>0){
 		    	$i=1;
 		    	while($rows = mysqli_fetch_array($exe)){
		    		$list 	= $this->editPagePublish($rows);
		    		$status = (($list['status']==1) ? "On" : "Off"); 
		    		$status_c = (($list['status']==1) ? "checked" : " ");
					$status_class = (($list['status']==1) ? "text-success" : "text-danger");
					$td_data 	  = "data-formclass='edit_service_tag' data-form='addServiceTag' data-option='".$this->encryptData($list['id'])."'";
		    		$layout .= "
	    				<tr class='nk-tb-item'>
	    					<td class='nk-tb-col openEditServiceTag' $td_data>
                                ".$i."
                            </td>
                            <td class='nk-tb-col openEditServiceTag' $td_data>
                                ".$list['id']."
                            </td>
                            <td class='nk-tb-col tb-col-md openEditServiceTag' $td_data>
                                <span class='text-primary'>".$this->publishContent($list['service_tag'])."</span>
	                        </td>    	                            
                            <td class='nk-tb-col tb-col-md'>
                            <div class='custom-control custom-switch'>
                                <input type='checkbox' class='custom-control-input changeServiceTagStatus' data-option='".$this->encryptData($list['id'])."' value='0' id='status_".$i."' name='save_as_draft' $status_c>
                                <label class='custom-control-label ' for='status_".$i."'><span class='$status_class'>$status </span></label>
                            </div>
                        	</td>
                            <td class='nk-tb-col nk-tb-col-tools'>
                                <ul class='nk-tb-actions gx-1'>
                                    <li class='nk-tb-action-hidden'>
                                        <button class='btn btn-trigger btn-icon deleteServiceTag' data-formclass='edit_service_tag' data-form='editServiceTag' data-option='".$this->encryptData($list['id'])."' ><em class='icon ni ni-trash-fill'></em></button>
                                    </li>
                                </ul>
                        	</td>
                        </tr>";
		    		$i++;
		    	}
 		    }
 		    return $layout;
		}

		// Add Service Tag

		function addServiceTag($input)
		{
			$validate_csrf = $this->validateCSRF($input);
			if($validate_csrf=='success') {
				$data = $this->cleanStringData($input);
				$token = $this->uniqueToken($data['service_tag']);
				$check_token = $this->check_query(SERVICE_TAGS_TBL,"id"," token='$token' AND token !='' ");
				if ($check_token==0) {
					$admin_id 		= $_SESSION["ecom_admin_id"];
					$curr 			= date("Y-m-d H:i:s");
					$query = "INSERT INTO ".SERVICE_TAGS_TBL." SET 
								token 			= '".$this->hyphenize($token)."',
								service_tag 	= '".$data['service_tag']."',
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
					$info = $this->getDetails(SERVICE_TAGS_TBL,"id,delete_status"," token='$token' ");
					if ($info['delete_status']==1) {
						return "The entered service tag already present on the trash. Kindly restore it from the trash section.";
					}else{
						return "The entered service tag already exists. ";
					}
				}
			}else{
				return $validate_csrf;
			}
		}

		// Get Service Tag Details

		function getServiceTagDetails($data)
		{	
			$layout = "";
			$id     = $this->decryptData($data);
			$query  = "SELECT * FROM  ".SERVICE_TAGS_TBL."  WHERE id='$id' ";
			$exe 	= $this->exeQuery($query);

			if(mysqli_num_rows($exe) > 0 ) {
				while ($list = mysqli_fetch_assoc($exe)) {
				$layout .= "<input type='hidden' name='token' value='".$this->encryptData($list['id'])."' id='token'>
			                <div class='form-group'>
			                    <label class='form-label'>Service Tag Title
			                        <en>*</en>
			                    </label>
			                    <input type='text' name='service_tag' id='service_tag'  value='".$list['service_tag']."' class='form-control' placeholder='Enter Service Tag Title' required>
			                </div>
				            <div class='form-error'>
					        </div>
					        <div class='form-group'>
					            <p class='float-right model_pt'>
					            <button type='button' class='btn btn-light close_modal' data-modal_id='editServiceTagModal'>Cancel</button>
					            <button type='submit' class='btn btn-primary'>Submit</button>
					            </p>
					        </div>";
				}
			}

			return $layout;
		}

		// Edit Service Tag

		function editServiceTag($input)
		{
			$validate_csrf = $this->validateCSRF($input);
			if($validate_csrf=='success'){
				$curr 		= date("Y-m-d H:i:s");
				$id 		=	$this->decryptData($input['token']);
				$data = $this->cleanStringData($input);
				$token = $this->uniqueToken($data['service_tag']);
				$check_token = $this->check_query(SERVICE_TAGS_TBL,"id"," token='$token' AND token !='' AND id!=".$id." ");
				if ($check_token==0) {
					$query 		= "UPDATE ".SERVICE_TAGS_TBL." SET 
								service_tag = '".$this->cleanString($input['service_tag'])."',
								updated_at = '$curr' WHERE id='$id' ";
					$exe 		= $this->exeQuery($query);
					if($exe){
						return 1;
					}else{
						return "Sorry!! Unexpected Error Occurred. Please try again.";
					}
				}else{
					$info = $this->getDetails(SERVICE_TAGS_TBL,"id,delete_status"," token='$token' ");
					if ($info['delete_status']==1) {
						return "The entered service tag already present on the trash. Kindly restore it from the trash section.";
					}else{
						return "The entered service tag already exists. ";
					}
				}	
			}else{
				return $validate_csrf;
			}
		}

		// Change Service Tag Status

		function changeServiceTagStatus($data)
		{
			$data = $this->decryptData($data);
			$info = $this -> getDetails(SERVICE_TAGS_TBL,"status"," id ='$data' ");
			if($info['status'] ==1){
				$query = "UPDATE ".SERVICE_TAGS_TBL." SET status='0' WHERE id='$data' ";
			}else{
				$query = "UPDATE ".SERVICE_TAGS_TBL." SET status='1' WHERE id='$data' ";
			}
			$up_exe = $this->exeQuery($query);
			if($up_exe){
				return 1;
			}
		}

		// Update Service Tag delete status

		function deleteServiceTag($data)
		{
			$data = $this->decryptData($data);
			$info = $this -> getDetails(SERVICE_TAGS_TBL,"delete_status"," id ='$data' ");
			$query = "UPDATE ".SERVICE_TAGS_TBL." SET delete_status='1' WHERE id='$data' ";
			$up_exe = $this->exeQuery($query);
			if($up_exe){
				return 1;
			}
		}

		// Restore Bolg Category

		function restoreServiceTag($data)
		{	
			$data = $this->decryptData($data);
			$query = "UPDATE ".SERVICE_TAGS_TBL." SET delete_status='0' WHERE id='$data' ";
			$up_exe = $this->exeQuery($query);
			if($up_exe) {
				return 1;
			}
		}

		/*----------------------------------------
				Manage Product Review
		-----------------------------------------*/

		function manageClassifiedProjects()
		{

			$layout = "";
			$q = "SELECT * FROM ".CONTRACTOR_PROJECT_TBL." WHERE delete_status='0' ORDER BY id DESC " ;
		    $query = $this->exeQuery($q);	
		    if(mysqli_num_rows($query) > 0){
		    	$i=1;
		    	while($details = mysqli_fetch_array($query)){
		    		$list 		    = $this->editPagePublish($details);
		    		$hire_details   = $this->getDetails(CONTRACTOR_TBL,"company_name","id='".$list['contractor_id']."'");
		    		$image          = $this->getDetails(CONTRACTOR_PROJECT_IMG_TBL,"*","project_id='".$list['id']."' ORDER BY id ASC LIMIT 1 ");
					$project_image  = ((isset($image['file_name']))? HIRE_UPLOADS.$image['file_name'] : ASSETS_PATH."no_img.png") ;# code...
		    		$request_status = (($list['status']==0)? "<span class='badge badge-dot badge-dot-xs badge-warning'>Inprocess</span>" : "<span class='badge badge-dot badge-dot-xs badge-success'>Approved</span>" );

		    		$app_status = (($list['verified_status']==1) ? "Verified" : "Not Verified"); 
		    		$app_status_c = (($list['verified_status']==1) ? "checked" : " ");
					$app_status_class = (($list['verified_status']==1) ? "text-success" : "text-warning");

					$status = (($list['status']==1) ? "On" : "Off"); 
		    		$status_c = (($list['status']==1) ? "checked" : " ");
					$status_class = (($list['status']==1) ? "text-success" : "text-danger");

					$td_data ="data-option='".$this->encryptData($list['id'])."'";


		    		$layout .= "
		    				<tr class='nk-tb-item ' >
	    					<td class='nk-tb-col open_project_detail' $td_data>
                                ".$i."
                            </td>
                            <td class='nk-tb-col open_project_detail' $td_data>
                                <img src='".$project_image."' width=50 />
                            </td>
                             <td class='nk-tb-col tb-col-md open_project_detail' $td_data>
                                <span class='text-primary'>".$this->publishContent($list['project_title'])."</span>
	                        </td>
	                        <td class='nk-tb-col open_project_detail' $td_data>
                                <p class='text-primary'>".$hire_details['company_name']."</p>
                            </td>
                            <td class='nk-tb-col open_project_detail' $td_data>
                                ".date("d-m-Y", strtotime($list['created_at']))."
                            </td>
                            <td class='nk-tb-col tb-col-md ' >
	                            <div class='custom-control custom-switch'>
                                    <input type='checkbox' class='custom-control-input changeProjectVisibleStatus'  data-link='products' data-option='".$this->encryptData($list['id'])."'   value='0'  id='status_".$i."' name='save_as_draft' $status_c>
                                    <label class='custom-control-label ' for='status_".$i."'><span class='$status_class'>$status </span></label>
                                </div>
	                        </td>
                            <td class='nk-tb-col tb-col-md'>
	                            <div class='custom-control custom-switch custom-control-sm'>
                                    <input type='checkbox' class='custom-control-input verifyClassifiedProject' data-option='".$this->encryptData($list['id'])."'   value='0'  id='app_status_".$i."' name='approval_status' $app_status_c>
                                    <label class='custom-control-label ' for='app_status_".$i."'><span class='$app_status_class'>$app_status </span></label>
                                </div>
	                        </td>
	                        <td class='nk-tb-col nk-tb-col-tools'>
                                <ul class='nk-tb-actions gx-1'>
                                     <li class='nk-tb-action-hidden'>
                                        <a href='javascript:void();' class='btn btn-trigger btn-icon trashClassifiedProject' data-toggle='tooltip' data-placement='top' title='Trash Project' data-option='".$this->encryptData($list['id'])."' >
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

		// Get ClassifiedProject Details

		function getClassifiedProjectDetails($data){
			$layout 	= "";
			$id 		= $this->decryptData($data);
			$info 		= $this->getDetails(CONTRACTOR_PROJECT_TBL,"*"," id='$id' ");
			$c_info		= $this->getDetails(CONTRACTOR_TBL,"*"," id='".$info['contractor_id']."' ");
			$info 		= $this->editPagePublish($info);
			$c_info 	= $this->editPagePublish($c_info);

			$status = (($info['verified_status']==1) ? "Verified" : "Not Verified"); 
			$status_class = (($info['verified_status']==1) ? "text-success" : "text-warning");

			$layout .="
						<div class='nk-block'>
	                    <div class='profile-ud-list'>
	                        <div class='profile-ud-item'>
	                            <div class='profile-ud wider enq_name_field'>
	                                <span class='profile-ud-label'>Name</span>
	                                <span class='profile-ud-value enq_name_align'>".$c_info['name']."</span>
	                            </div>
	                        </div>
	                    </div>
	                    <div class='profile-ud-list'>
	                        <div class='profile-ud-item'>
	                             <div class='profile-ud wider enq_name_field'>
	                                <span class='profile-ud-label'>Product Name</span>
	                                <span class='profile-ud-value  enq_name_align'>".$info['project_title']."</span>
	                            </div>
	                        </div>
	                    </div>
	                     <div class='profile-ud-list'>
	                       <div class='profile-ud-item '>
	                            <div class='profile-ud wider enq_name_field'>
	                                <span class='profile-ud-label'>Mobile Number</span>
	                                <span class='profile-ud-value enq_name_align'>".$c_info['mobile']."</span>
	                            </div>
	                        </div>
	                    </div>
	                    <div class='profile-ud-list'>
	                        <div class='profile-ud-item'>
	                             <div class='profile-ud wider'>
	                                <span class='profile-ud-label'>Email Address</span>
	                                <span class='profile-ud-value'>".$c_info['email']."</span>
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

	                    <div class='profile-ud-list'>
	                        <div class='profile-ud-item'>
	                             <div class='profile-ud wider enq_name_field'>
	                                <span class='profile-ud-label'>Status</span>
	                                <span class='profile-ud-value $status_class enq_name_align'>".$status."</span>
	                            </div>
	                        </div>
	                    </div>
	                </div>
	                <div class='nk-block'>
	                    <div class='nk-block-head nk-block-head-line'>
	                        <h6 class='title overline-title text-base'>Message</h6>
	                    </div>
	                    <div class='enq_message'>
	                        <p>".$info['description']."</p>
	                    </div>
	                </div>
	                <div class='nk-block'>
	                    <div class='nk-block-head nk-block-head-line'>
	                        <h6 class='title overline-title text-base'>Project Images</h6>
	                    </div>
	                    <div class='container'>
							<div class='row'>";

	            $q = "SELECT file_name FROM ".CONTRACTOR_PROJECT_IMG_TBL." WHERE project_id = '".$info['id']."' AND contractor_id = '".$c_info['id']."' " ;
		    	$query = $this->exeQuery($q);
		    	if(mysqli_num_rows($query) > 0){
		    		$i = 1;
		    		while($list = mysqli_fetch_array($query)){
		    			$images = $this->editPagePublish($list);
		    			$project_image  = ((isset($images['file_name']))? HIRE_UPLOADS.$images['file_name'] : ASSETS_PATH."no_img.png") ;
						$layout .="<div id='preview".$i."' class='col-md-4 preve' style='padding:10px;'>
									  <img src='".$project_image."' width='200' height='100'/>
						            </div>";  
						$i++;
		    		}
		    	}else{
		    		$project_image  = ASSETS_PATH.'no_img.png';
						$layout .="<div class='col-md-4' style='padding:10px;'>
									  <img src='".$project_image."' width='200' height='100'/>
						            </div>";
		    	}
		    	$layout .="
		    				</div>
						   </div>
					       </div>";	
			return $layout;
		}

		// Trash Classified Project

		function trashClassifiedProject($data)
		{
			$data   = $this->decryptData($data);
			$info   = $this->getDetails(CONTRACTOR_PROJECT_TBL,"contractor_id","id='".$data."'");
			$h_info = $this->getDetails(CONTRACTOR_TBL,"id","id='".$info['contractor_id']."'");
			$query  = "UPDATE ".CONTRACTOR_PROJECT_TBL." SET delete_status='1' WHERE id='$data' ";
			$up_exe = $this->exeQuery($query);
			if($up_exe) {
				$project_detail = $this->getDetails(CONTRACTOR_PROJECT_TBL,"id,COUNT(id) as count,contractor_id","delete_status='0' AND status='1' AND contractor_id = '".$info['contractor_id']."'  ");

				$project_count = (($project_detail['id'])? $project_detail['count'] : 0);
				
				$query  = "UPDATE ".CONTRACTOR_TBL." SET 
					   total_projects = ".$project_count."
					   WHERE id = '".$h_info['id']."' 
					   ";
				$exe = $this->exeQuery($query);
				return 1;
			}
		}

		// Restore Classified Project

		function restoreClassifiedProject($data)
		{	
			$data   = $this->decryptData($data);
			$info   = $this->getDetails(CONTRACTOR_PROJECT_TBL,"contractor_id","id='".$data."'");
			$h_info = $this->getDetails(CONTRACTOR_TBL,"id","id='".$info['contractor_id']."'");
			$query  = "UPDATE ".CONTRACTOR_PROJECT_TBL." SET delete_status='0' WHERE id='$data' ";
			$up_exe = $this->exeQuery($query);
			if($up_exe) {
				$project_detail = $this->getDetails(CONTRACTOR_PROJECT_TBL,"id,COUNT(id) as count,contractor_id","delete_status='0' AND status='1' AND contractor_id = '".$info['contractor_id']."'  ");
				$project_count = (($project_detail['id'])? $project_detail['count'] : 0);
				$query  = "UPDATE ".CONTRACTOR_TBL." SET 
					   total_projects = ".$project_count."
					   WHERE id = '".$h_info['id']."' 
					   ";
				$exe = $this->exeQuery($query);
				return 1;
			}
		}

		// Change verify  Status

		function changeClassifiedProjectStatus($data)
		{
			$data = $this->decryptData($data);
			$info = $this -> getDetails(CONTRACTOR_PROJECT_TBL,"verified_status"," id ='$data' ");
			if($info['verified_status'] ==1){
				$query = "UPDATE ".CONTRACTOR_PROJECT_TBL." SET verified_status='0' WHERE id='$data' ";
			}else{
				$query = "UPDATE ".CONTRACTOR_PROJECT_TBL." SET verified_status='1' WHERE id='$data' ";
			}
			$up_exe = $this->exeQuery($query);
			if($up_exe){
				return 1;
			}
		}

		// Change Visible  Status

		function calssifiedProjectVisibleStatus($data)
		{
			$data = $this->decryptData($data);
			$info = $this -> getDetails(CONTRACTOR_PROJECT_TBL,"status"," id ='$data' ");
			if($info['status'] ==1){
				$query = "UPDATE ".CONTRACTOR_PROJECT_TBL." SET status='0' WHERE id='$data' ";
			}else{
				$query = "UPDATE ".CONTRACTOR_PROJECT_TBL." SET status='1' WHERE id='$data' ";
			}
			$up_exe = $this->exeQuery($query);
			if($up_exe){
				return 1;
			}
		}

		/*----------------------------------------
				Manage Hire Viewd Contacts
		-----------------------------------------*/

		function manageHireViewedContacts()
		{
			$layout = "";
			$q = "SELECT CV.id,CV.name,CV.mobile,CV.contractor_id,CV.status,CV.created_at,CV.updated_at,H.name as h_name,H.email,H.mobile as h_mobile FROM ".CONTRACTOR_DETAILS_VIEWED_TBL."  CV LEFT JOIN ".CONTRACTOR_TBL." H ON (H.id=CV.contractor_id) WHERE  CV.status='1' ORDER BY CV.id DESC " ;
		    $query = $this->exeQuery($q);	
		    if(mysqli_num_rows($query) > 0){
		    	$i=1;
		    	while($details = mysqli_fetch_array($query)){
		    		$list 		= $this->editPagePublish($details);

		    		$layout .= "
		    				<tr class='nk-tb-item' >
		    					<td class='nk-tb-col'>
	                                ".$i."
	                            </td>
	                             <td class='nk-tb-col'>
	                                ".date("d-m-Y", strtotime($list['created_at']))."
	                            </td>
	                           
	                            <td class='nk-tb-col tb-col-md'>
	                                <span class='text-primary'>".$this->publishContent($list['name'])."</span>
		                        </td>
		                        <td class='nk-tb-col tb-col-md'>
	                                <span class='text-primary'>".$this->publishContent($list['mobile'])."</span>
		                        </td>
		                        <td class='nk-tb-col tb-col-md'>
	                                <em class='icon ni ni-user'></em> <span >". $list['h_name']."</span><br>
	                                <em class='icon ni ni-mail'></em> <span >". $list['email']."</span><br>
	                                <em class='icon ni ni-mobile'></em> <span >". $list['mobile']."</span>
		                        </td>
		                        ";

		    		$i++;
		    	}
		    }
		    	return $layout;
		}

		// Trash Hire Viewed contact

		function trashHireViewedContact($data)
		{
			$data   = $this->decryptData($data);
			$query  = "UPDATE ".CONTRACTOR_DETAILS_VIEWED_TBL." SET delete_status='1' WHERE id='$data' ";
			$up_exe = $this->exeQuery($query);
			if($up_exe) {
				return 1;
			}
		}

	/*-----------Dont'delete---------*/

	}


?>




