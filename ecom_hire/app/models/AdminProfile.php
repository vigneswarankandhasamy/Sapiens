<?php
	require_once 'Model.php';
	require_once 'FileUploader.php';
	require_once 'config/config.php';
	require_once 'app/core/classes/PHPMailerAutoload.php';

	class AdminProfile extends Model
	{

		/*--------------------------------------------- 
					  Login
		----------------------------------------------*/

		// Login Activity

		function manageloginActivity($limit)
	  	{
	  		$layout = "";

		    $q ="SELECT * FROM ".ADMIN_SESSION_TBL." WHERE 1 AND logged_id='".$_SESSION["ecom_contractor_id"]."'  ORDER BY id DESC $limit " ;
		    $query = $this->exeQuery($q);
		    if(mysqli_num_rows($query)>0){
		    	$i=1;
		    	while($row = mysqli_fetch_array($query)){
		    		$list = $this->editPagePublish($row);
		    		$layout .= "
		    			<tr>
                            <td class='tb-col-os'>".$list['auth_user_agent']."</td>
                            <td class='tb-col-ip'><span class='sub-text'>".$list['auth_ip_address']."</span></td>
                            <td class='tb-col-time'><span class='sub-text'>".date("M d, Y h:i A  ",strtotime($list['session_in']))."</span></td>
                        </tr>";
	                $i++;
		    	}
		    }
		    return $layout;
	  	}

	  	/*---------------------------------------------
					Update	Admin Profile
		----------------------------------------------*/

		// Update Profile

		function updateAdminProfile($input)
		{	
			$data 			= $this->cleanStringData($input);
			$validate_csrf  = $this->validateCSRF($data);
			if ($validate_csrf=="success") {
				$id 	= $_SESSION["ecom_contractor_id"];
				$check 	= $this->check_query(CONTRACTOR_TBL,"id"," (email='".$data['email']."' OR mobile = '".$data['mobile']."') AND id!='".$id."'  ");
				if($check==0) {
					$admin_id 		= $_SESSION["ecom_contractor_id"];
					$curr 			= date("Y-m-d H:i:s");
					$save_as_draft 	= isset($data['save_as_draft']) ? 1 : 0;
					$normal_pass    = $this->mobileToken(8);
					$h_pass         = $this->encryptPassword($normal_pass);
					$name_string    = str_replace(' ', '_', $data['name']);
	                $token          = strtolower($name_string);
					$query 			= "UPDATE  ".CONTRACTOR_TBL." SET 
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
				$id 	= $_SESSION["ecom_contractor_id"];
				$check 	= $this->check_query(CONTRACTOR_TBL,"id", " id= '".$_SESSION['ecom_contractor_id']."' AND
                      hash_password = '".$this->encryptPassword($input['password'])."' ");

				if($check==1) {
					$curr 			= date("Y-m-d H:i:s");
					$normal_pass    = $input['new_password'];
					$h_pass         = $this->encryptPassword($normal_pass);
					$query 			= "UPDATE  ".CONTRACTOR_TBL." SET 
								hash_password 		= '".$h_pass."',
								password 			= '".$normal_pass."',
								password_update 	= '$curr',					
								updated_at 			= '$curr' WHERE id='$id' ";
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



		// time 

		function to_time_ago( $time )
		{
		   $difference =  $time;
		   if( $difference < 1 )
		   {
		      return ' Few seconds ago';
		   }
		   $time_rule = array (
		      12 * 30 * 24 * 60 * 60 => 'year',
		      30 * 24 * 60 * 60 => 'month',
		      24 * 60 * 60 => 'day',
		      60 * 60 => 'hour',
		      60 => 'minute',
		      1 => 'second'
		   );
		   foreach( $time_rule as $sec => $my_str )
		   {
		      $res = $difference / $sec;
		      if( $res >= 1 )
		      {
		         $t = round( $res );
		         return $t . ' ' . $my_str .
		         ( $t > 1 ? 's' : '' ) . ' ago';
		      }
		   }
		}


		/*----------------------------------------------
		       Update mandatory profile pop-up form
	  	-----------------------------------------------*/

	  	//update  Profile info 

	  	function updateProfileInfo($input)
		{ 
			$validate_csrf = $this->validateCSRF($input);
			if($validate_csrf=='success') {

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

				if($input['experience'] > 1) {
					if($input['experience_duration_pop_up']=="month"){
						$experience_duration = "Months";
					} else {
						$experience_duration = "Years";
					}
				} else {
					if($input['experience_duration_pop_up']=="month"){
						$experience_duration = "Month";
					} else {
						$experience_duration = "Year";
					}
				}
				
				$curr 			= date("Y-m-d H:i:s");
	 			$query = "UPDATE ".CONTRACTOR_TBL." SET 
					company_name			= '".$this->cleanString($input['title_name'])."',
					experience 				= '".$this->cleanString($input['experience'])."',
					experience_duration     = '".$experience_duration."',
					profile_type 			= '".$profile."',
					service_tags 			= '".$service_tags."',
					updated_at 				= '$curr' WHERE id='".$_SESSION['ecom_contractor_id']."' ";
				$exe 	= $this->exeQuery($query);
				if($exe){
					return 1;
				}else{
					return "Sorry!! Unexpected Error Occurred. Please try again.";
				}
				
			}else{
				return "Sorry!! Unexpected Error Occurred. Please try again.";
			}
		}

		// Update Contact info
		function updateContactInfo($input)
		{ 
			$validate_csrf = $this->validateCSRF($input);
			if($validate_csrf=='success'){
				$curr 			= date("Y-m-d H:i:s");
				$state_info     = $this->getDetails(STATE_TBL,"name","id='".$input['state_id']."'");
	 			$query = "UPDATE ".CONTRACTOR_TBL." SET 
					address 				= '".$this->cleanString($input['address'])."',
					city 					= '".$this->cleanString($input['city'])."',
					pincode 				= '".$this->cleanString($input['pincode'])."',
					gst_no 					= '".$this->cleanString($input['gst_no'])."',
					state_id 				= '".$this->cleanString($input['state_id'])."',
					state_name 				= '".$state_info['name']."',
					invite_status 			= '1',
					updated_at 				= '$curr' WHERE id='".$_SESSION['ecom_contractor_id']."' ";
				$exe 	= $this->exeQuery($query);
				if($exe){
					return 1;
				}else{
					return "Sorry!! Unexpected Error Occurred. Please try again.";
				}
			}else{
				return "Sorry!! Unexpected Error Occurred. Please try again.";
			}
		}



		/*-------------------------------------
				Update Company Profile
	  	-------------------------------------*/

	  	// Edit Company Profile 

		function updateCompany($input,$image)
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

			
				$curr 			= date("Y-m-d H:i:s");
				$save_as_draft 	= isset($input['save_as_draft']) ? 1 : 0;
				$state_info     = $this->getDetails(STATE_TBL,"name","id='".$input['state_id']."'");
				$check_mobile 	= $this -> check_query(CONTRACTOR_TBL,"id","mobile='".$input['mobile']."' AND id!='$id' ");
				if($check_mobile==0) {
					$check_email = $this -> check_query(CONTRACTOR_TBL,"id","email='".$input['email']."' AND id!='$id' ");
					if($check_email==0) {
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
										created_at 				= '$curr' WHERE id='$id' ";
										// var_dump($query);
									$exe 	= $this->exeQuery($query);
								if($exe){
									return 1;
								}else{
									return "Sorry!! Unexpected Error Occurred. Please try again.";
								}

							}else{
									return $image['message'];
							}
						if($exe){
							return 1;
						}else{
							return "Sorry!! Unexpected Error Occurred. Please try again.";
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
					$layout.= "<div class='form-group col-md-3'>
									<div class='custom-control custom-control-sm custom-checkbox'>
									    <input type='checkbox' class='custom-control-input classified_profile' value='".$list['id']."' name='profile[]' id='customCheck".$i."' $selected>
									    <label class='custom-control-label' for='customCheck".$i."'> <label class='form-label'>".$list['profile']."
									    </label></label>
									 </div>
								</div>";
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
	  			$current = $service_tags;
	  		}
	  		$layout = "";
	  		$q = "SELECT id,token,service_tag FROM ".SERVICE_TAGS_TBL." WHERE status='1' AND delete_status='0' " ;
	  		$query = $this->exeQuery($q);
		    if(@mysqli_num_rows($query)>0){
		    	$i=0;
		    	while($list = mysqli_fetch_array($query)){
					$selected = ((in_array($list['id'],$current)) ? 'selected' : '');
					$layout.= "<option value='".$list['id']."' $selected>".$list['service_tag']."</option>";
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
		    	$layout.="<option value=''>Select State</option>";
		    	while($list = mysqli_fetch_array($query)){
					$selected = (($list['id']==$current) ? 'selected' : '');
					$layout.= "<option value='".$list['id']."' $selected>".$list['name']."</option>";
	                $i++;
		    	}
		    }
		    return $layout;
	  	}

	  	// Enquiry Data 

		function enquiryList($from,$to,$type)
		{
			$users = array();

			 if($type=="unread") {
	        	$read_status_filter = "AND read_status='0' ";
	        } elseif ($type=='read') {
	        	$read_status_filter = "AND read_status='1' ";
	        } else {
	        	$read_status_filter = "";
	        }

			if( $from==0 && $to==0 )
			{
				$q = "SELECT * FROM ".CONTRACTOR_ENQUIRY_TBL."  WHERE contractor_id='".$_SESSION['ecom_contractor_id']."' ".$read_status_filter." AND delete_status='0'  ORDER BY id DESC" ;
			} elseif($from=="today") {
				$q = "SELECT * FROM ".CONTRACTOR_ENQUIRY_TBL."  WHERE contractor_id='".$_SESSION['ecom_contractor_id']."' ".$read_status_filter." AND delete_status='0' AND DATE(created_at)=CURDATE() ORDER BY id DESC" ;
			} elseif($from==$to) {
				$from = date("Y-m-d ",strtotime($from));
				$to   = date("Y-m-d ",strtotime($to));
 				$q = "SELECT * FROM ".CONTRACTOR_ENQUIRY_TBL."  WHERE contractor_id='".$_SESSION['ecom_contractor_id']."' ".$read_status_filter." AND delete_status='0' AND DATE(created_at)='".$from."' ORDER BY id DESC" ;
			} else {
				$from = date("Y-m-d ",strtotime($from));
				$to = date("Y-m-d ",strtotime($to));
				$q = "SELECT * FROM ".CONTRACTOR_ENQUIRY_TBL."  WHERE contractor_id='".$_SESSION['ecom_contractor_id']."' ".$read_status_filter." AND delete_status='0' AND created_at BETWEEN '$from' AND '$to'  ORDER BY id DESC" ;
			}

		    $query = $this->exeQuery($q);

		    if(mysqli_num_rows($query) > 0){
		    	$i=1;
		    	while($list = mysqli_fetch_array($query)){
		    			$element = array();

						$element[] =  	$i;
						$element[] =  	$list['name'];
						$element[] =  	$list['email'];
						$element[] =  	$list['mobile'];
						$element[] =  	$list['message'];
						$element[] =  	date("d/m/Y [h:i]",strtotime($list['created_at']));
					  	$users[] =  	$element;
					  	$i++;

		    	}
		    }
		    return $users;
		}

		

	
	/*-----------Dont'delete---------*/

	}


?>




