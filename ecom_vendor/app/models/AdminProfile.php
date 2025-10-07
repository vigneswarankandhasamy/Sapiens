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

		    $q ="SELECT * FROM ".ADMIN_SESSION_TBL." WHERE 1 AND logged_id='".$_SESSION["ecom_vendor_id"]."'  ORDER BY id DESC $limit " ;
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
				$id 	= $_SESSION["ecom_vendor_id"];
				$check 	= $this->check_query(VENDOR_TBL,"id"," (email='".$data['email']."' OR mobile = '".$data['mobile']."') AND id!='".$id."'  ");
				if($check==0) {
					$admin_id 		= $_SESSION["ecom_vendor_id"];
					$curr 			= date("Y-m-d H:i:s");
					$save_as_draft 	= isset($data['save_as_draft']) ? 1 : 0;
					$normal_pass    = $this->mobileToken(8);
					$h_pass         = $this->encryptPassword($normal_pass);
					$name_string    = str_replace(' ', '_', $data['name']);
	                $token          = strtolower($name_string);
					$query 			= "UPDATE  ".VENDOR_TBL." SET 
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


		// Change Password

		function changePasswordAdmin($input)
		{	

			$validate_csrf  = $this->validateCSRF($input);

			if ($validate_csrf=="success") {
				$id 	= $_SESSION["ecom_vendor_id"];
				$check 	= $this->check_query(VENDOR_TBL,"id", " id= '".$_SESSION['ecom_vendor_id']."' AND
                      password= '".$this->encryptPassword($input['password'])."' ");

				if($check==1) {
					$curr 			= date("Y-m-d H:i:s");
					$normal_pass    = $input['new_password'];
					$h_pass         = $this->encryptPassword($normal_pass);
					$query 			= "UPDATE  ".VENDOR_TBL." SET 
								password 				= '".$h_pass."',
								password_normal 			= '".$normal_pass."',
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



	/*-----------Dont'delete---------*/

	}


?>




