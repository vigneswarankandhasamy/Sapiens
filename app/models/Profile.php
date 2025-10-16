<?php

require_once 'Model.php';
require_once 'app/core/classes/PHPMailerAutoload.php';

class Profile extends Model
{	
	/*----------------------------------------------
				Edit Profile
	----------------------------------------------*/

	function editUserProfile($data)
	{
		if(isset($_SESSION['edit_profile_key'])){
			if($this->cleanString($data['fkey']) == $_SESSION['edit_profile_key']){
				$curr  		= date("Y-m-d H:i:s");
				$user_id 	= $_SESSION['user_session_id'];
				$check_email = $this -> check_query(CUSTOMER_TBL,"id","email='".$data['email']."' AND id!='".$user_id."' ");
				if($check_email==0){
					$check_mobile = $this -> check_query(CUSTOMER_TBL,"id","mobile='".$data['mobile']."' AND id!='".$user_id."' ");
					if($check_mobile==0){
						$query = "UPDATE ".CUSTOMER_TBL." SET 
									name 		= '".$this->cleanString($data['name'])."',
									email 		= '".$data['email']."',
									mobile 		= '".$data['mobile']."',
									updated_at 	= '".$curr."' WHERE id='".$user_id."' AND status= '1' ";
						$exe = $this->exeQuery($query);
						if($exe){
							unset($_SESSION['edit_profile_key']);
							return 1;
						}else{
							return "Sorry!! Unexpected Error Occurred. Please try again.";
						}

					}else{
						return $this->errorMsg("Entered mobile number is already registered. Please change the mobile number or Try login.");
					}
				}else{
					return $this->errorMsg("Entered email address is already registered. Please change the email or Try login.");
				}

			}else{
				return "Sorry Invalid User Entry. Try Re login to the Panel to continue.";
			}
		}else{
			return "Sorry Invalid User Entry. Try Re login to the Panel to continue.";
		}
	}

	// Change Admin Password

	function changeUserPassword($data)
	{
		if(isset($_SESSION['change_password_key'])){
			if(($data['fkey']) == $_SESSION['change_password_key']){
				$curr 			= date("Y-m-d H:i:s");
   		 		$check_password = $this->check_query(CUSTOMER_TBL,"id", " id= '".$_SESSION['user_session_id']."' AND
			          			  password= '".$this->encryptPassword($data['password'])."' ");
   		 		$userinfo 		= $this -> getDetails(CUSTOMER_TBL,"id,password"," id= '".$_SESSION['user_session_id']."'  ");	
			    if($check_password==1){
			    	if($userinfo['password']!=$this->encryptPassword($data['new_password'])){
				        $query= "UPDATE ".CUSTOMER_TBL." SET
				                password= '".$this->encryptPassword($data['new_password'])."',
				                updated_at='".$curr."' WHERE id='".$_SESSION['user_session_id']."' "; 
		                $exe = $this->exeQuery($query);
		                return 1;
					}else{
						return ("Your Current Password is same as new paswword.");
					}
			    }else{
			        return ("Your Current Password is wrong.");
			    }
			}else{
				return "Sorry Invalid User Entry. Try Re login to the Panel to continue.";
			}
		}else{
			return "Sorry Invalid User Entry. Try Re login to the Panel to continue.";
		}
	}

	// Get State list

	function getStatelist($current="")
  	{
  		$layout = "";
  		$query 		= "SELECT * FROM ".STATE_TBL." WHERE status='1' AND delete_status='0' " ;
  		$exe = $this->exeQuery($query);
	    if(@mysqli_num_rows($exe)>0){
	    	while($list = mysqli_fetch_array($exe)){
				$selected = (($list['id']==$current) ? 'selected' : '');
				$selected = (($selected=="selected")? $selected : (($list['id']==31) ? 'selected' : '') );
				$layout.= "<option value='".$list['id']."' ".$selected.">".$list['name']."</option>";
	    	}
	    }
	    return $layout;
  	}

  	
  	/*----------------------------------------------
				Location List 
	----------------------------------------------*/

	function getLocationlistForAddress($current="")
  	{	
  		$location_group_layout = "";
  		$location_layout       = array();
  		$q = "SELECT LG.id,LG.token,LG.group_name,LG.state_id,LG.status,LG.delete_status,LA.location,LA.group_id FROM ".LOCATIONGROUP_TBL." LG LEFT JOIN ".LOCATION_TBL." LA ON (LA.group_id=LG.id) WHERE  LG.delete_status='0' AND LG.status='1' AND LA.group_id=LG.id AND LG.status='1' GROUP BY LG.id ASC" ;
	    $query = $this->exeQuery($q);	
	    if(mysqli_num_rows($query) > 0){
	    	while($details = mysqli_fetch_array($query)){
	    		$list 	   	    		= $this->editPagePublish($details);
	    		$location_layout[] 		= $this->getLocationArealist($list['id']);  
	    		$selected       		= (($list['id']==$current) ? 'selected' : '');
	    		$location_group_layout .= "<option value='".$list['id']."' data-state_id='".$list['state_id']."' $selected>".$list['group_name']."</option>";
	    	}
	    }

	    $result['group_layout']    = $location_group_layout;
	    $result['location_layout'] = $location_layout;
	    return $result;
  	}

  	// Get locations for selected Location group

  	function getLocationArealist($group_id,$current="")
  	{	
		$layout         = "";
  		$location_group = $this->getDetails(LOCATIONGROUP_TBL,"*"," id='".$group_id."' ");
        $q = "SELECT L.id,L.token,L.location,L.pincode,L.longitude,L.latitude,L.group_id,L.status,L.delete_status,G.id as group_id,G.city_id FROM ".LOCATION_TBL." L LEFT JOIN ".GROUP_TBL." G ON (G.id=L.group_id) WHERE G.city_id='".$location_group['id']."'  AND L.delete_status='0' AND L.status='1' ORDER BY L.id ASC";
	    $exe = $this->exeQuery($q);	
	    if(mysqli_num_rows($exe) > 0){
	    	$layout       .= "<option value=''>Select Area</option>";
	    	while($details = mysqli_fetch_array($exe)){
	    		$list 	   = $this->editPagePublish($details); 
	    		$selected  = (($list['id']==$current) ? 'selected' : '');
				$layout   .= "<option value='".$list['id']."' data-pincode='".$list['pincode']."'  ".$selected.">".$list['location']."</option>";
	    	}
	    }
	    return $layout;
  	}

	// manage Address

    function manageAddress()
    {
	    $layout 	= "";
	    $user_id 	= @$_SESSION['user_session_id'];
	    $query 		= "SELECT * FROM ".CUSTOMER_ADDRESS_TBL." WHERE user_id='".$user_id."' AND delete_status='0' ";
	    $exe = $this->exeQuery($query);
		    if (mysqli_num_rows($exe) > 0) {
			    while ($row  = mysqli_fetch_assoc($exe)) {
				    $list    = $this->editPagePublish($row);
				    $default = (($list['default_address'] == 1) ? "default" : "");
				    $address = (($list['default_address'] == 1) ? "Default Address" : "");

				    $gst_name_data        = "";
				    $gstin_number_data    = "";

				    if($list['gst_name'] != "") {
				    	$gst_name_data 	  = "<p>GST Name : ".$this->publishContent($list['gst_name'])."</p>";
				    }
				    if($list['gstin_number'] != "") {
				    	$gstin_number_data    = "<p>GSTIN : ".$this->publishContent($list['gstin_number'])."</p>";
				    }

				    $layout .="
				    <div class='address-card'>
				        <div class='address-name'>".$this->publishContent($list['user_name'])."</div>
				        <div class='address-details'>
				            <div class='address-text'>
				                <i class='fas fa-map-marker-alt'></i>
				                <span>".$this->publishContent($list['address']).', '.$this->publishContent($list['landmark']).', '.$list['area_name'].', '.$list['city'].', '.$list['state_name'].' - '.$list['pincode']."</span>
				            </div>
				            <div class='phone-text'>
				                <i class='fas fa-phone-alt'></i>
				                <span>Ph (+91) - ".$list['mobile']."</span>
				            </div>
				        </div>
				        ".$gst_name_data." ".$gstin_number_data."
				        <div class='card-actions'>
				            <button class='action-btn delete-btn delete_address' data-id='".$this->encryptData($list['id'])."' title='Delete Address'>
				                <i class='fas fa-trash-alt'></i>
				            </button>
				            <button class='action-btn edit-btn editAddressPopup' data-option='".$this->encryptData($list['id'])."' title='Edit Address'>
				                <i class='fas fa-edit'></i>
				            </button>
				            <button class='action-btn default-btn make_default' data-id='".$this->encryptData($list['id'])."' title='Set as Default'>
				                <i class='fas fa-check-circle'></i>
				            </button>
				        </div>
				        ".($list['default_address'] == 1 ? "<div class='default-badge'>Default Address</div>" : "")."
				    </div>";
			    }
	    	}
	    return $layout;
    }

    // Add Cart Shipping Address

    function addCartShippingAddress($input)
    {	
    	if(isset($_SESSION['new_shipping_address_key'])){
			$data = $this->cleanStringData($input);
			if($this->cleanString($data['fkey']) == $_SESSION['new_shipping_address_key']){
				$curr  			= date("Y-m-d H:i:s");
				$user_id 		= $_SESSION['user_session_id'];
				$cus_name 		= $this->hyphenize($data['name']);
				$check_token 	= $this->check_query(CUSTOMER_ADDRESS_TBL,"id"," token='".$cus_name."' ");
				if ($check_token==0) {
					$token = $cus_name;
				}else{
					$token = $cus_name."-".$this->generateRandomString("5");
				}
				$state     = $this->getDetails(STATE_TBL,"name","id='".$data['state_id']."'");
				$city      = $this->getDetails(LOCATIONGROUP_TBL,"group_name","id='".$data['city']."'");
				$location  = $this->getDetails(LOCATION_TBL,"location","id='".$data['location_area']."'");

				// Check if default address already exist

				$check_def_add = $this->check_query(CUSTOMER_ADDRESS_TBL,"default_address", "default_address='1' AND delete_status='0' AND user_id='".$user_id."' ");
				$make_default  = ($check_def_add==0)? 1 : 0 ;
				
				echo $query = "INSERT INTO ".CUSTOMER_ADDRESS_TBL." SET 
							token 			= '".$token."',
							user_name 		= '".$data['name']."',
							mobile 			= '".$data['mobile']."',
							address 		= '".$data['address']."',
							landmark 		= '".$data['landmark']."',
							city 			= '".$city['group_name']."',
							city_id 		= '".$data['city']."',
							state_id 		= '".$data['state_id']."',
							state_name 		= '".$state['name']."',
							pincode 		= '".$data['pincode']."',
							gst_name 		= '".$data['gst_name']."',
							gstin_number 	= '".$data['gstin_number']."',
							user_id 		= '".$user_id."',
							default_address = '".$make_default."',
 							status			= '1',
							created_at 		= '".$curr."',
							updated_at 		= '".$curr."' ";
				$exe = $this->exeQuery($query);
				if($exe){
					unset($_SESSION['new_shipping_address_key']);
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

    // Datas For edit address pop-up form

    function editAddressPopup($id='')
	{	
		$_SESSION['edit_shipping_address_key'] = $this->generateRandomString("40");
		$add_id 			= $this->decryptData($id);
		$info 				= $this->getDetails(CUSTOMER_ADDRESS_TBL,"*","id='".$add_id."'");
		$info   			= $this->editPagePublish($info);
		$location_group_drp	= $this->getStatelist($info['state_id']);
		$location_drp 		= $this->getLocationlistForAddress($info['city_id'],$info['area_id']);
		$location_area 		= $this->getLocationArealist($info['city_id'],$info['area_id']);
		$layout = '
			<input type="hidden" value="'.$_SESSION['edit_shipping_address_key'].'" name="fkey">
            <input type="hidden" value="'.$id.'" name="token" id="token">
            
            <!-- Address Details Section -->
            <div class="form-section">
                <div class="section-header">
                    <h5>Address Details</h5>
                    <div class="section-divider"></div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="name" class="form-label">Name <span class="required">*</span></label>
                            <input type="text" name="name" id="name" value="'.$info["user_name"].'" placeholder="Enter your full name" class="form-input">
                            <div class="error-message" id="nameError"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="mobile" class="form-label">Mobile Number <span class="required">*</span></label>
                            <input type="text" name="mobile" id="mobile" value="'.$info["mobile"].'" placeholder="Enter your mobile number" class="form-input" maxlength="10">
                            <div class="error-message" id="mobileError"></div>
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="address" class="form-label">Address <span class="required">*</span></label>
                    <textarea name="address" id="address" class="form-input" placeholder="Enter your complete address" rows="3">'.$info["address"].'</textarea>
                    <div class="error-message" id="addressError"></div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="landmark" class="form-label">Landmark <span class="required">*</span></label>
                            <input type="text" name="landmark" id="landmark" value="'.$info["landmark"].'" placeholder="Enter landmark" class="form-input">
                            <div class="error-message" id="landmarkError"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="city" class="form-label">Select City <span class="required">*</span></label>
                            <select name="city" id="edit_city" class="form-select" readonly="readonly">
                               '.$location_drp['group_layout'].' 
                            </select>
                            <div class="error-message" id="cityError"></div>
                        </div>
                    </div>
                </div>
                
                <input type="hidden" name="location_area" id="edit_location_area" value='.$info['area_id'].'>
                <div class="form-group Edit_Location_area_dropdown">
                    <label for="area" class="form-label">Select Area <span class="required">*</span></label>
                    <select name="area" id="edit_area_select" class="form-select">
                        '.$location_area.'
                    </select>
                    <div class="error-message" id="areaError"></div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="state_id" class="form-label">Select State <span class="required">*</span></label>
                            <select name="state_id" id="edit_state_id" class="form-select">
                                '.$location_group_drp.' 
                            </select>
                            <div class="error-message" id="stateError"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="pincode" class="form-label">Pincode <span class="required">*</span></label>
                            <input type="text" name="pincode" id="edit_pincode" value="'.$info["pincode"].'" placeholder="Pincode" class="form-input" readonly="readonly">
                            <div class="error-message" id="pincodeError"></div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- GST Details Section -->
            <div class="form-section">
                <div class="section-header">
                    <h5>GST Details (Optional)</h5>
                    <div class="section-divider"></div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="gst_name" class="form-label">GST Name</label>
                            <input type="text" name="gst_name" id="gst_name" value="'.$info["gst_name"].'" placeholder="Enter GST name" class="form-input">
                            <div class="error-message" id="gstNameError"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="gstin_number" class="form-label">GSTIN Number</label>
                            <input type="text" name="gstin_number" id="gstin_number" value="'.$info["gstin_number"].'" placeholder="Enter GSTIN number" class="form-input">
                            <div class="error-message" id="gstinNumberError"></div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Form Actions -->
            <div class="form-actions">
                <button type="button" class="btn-secondary" onclick="window.hideModal(\'editAddressModal\')">
                    <i class="fas fa-times"></i> Cancel
                </button>
                <button type="submit" class="btn-primary">
                    <i class="fas fa-save"></i> Update Address
                </button>
            </div>
			';
		return $layout;
	}

	//edit address insert
	function editCartShippingAddress($data)
	{	

		$layout = "";
		if(isset($_SESSION['edit_shipping_address_key'])){
			if(($data['fkey']) == $_SESSION['edit_shipping_address_key']){
				$curr  		= date("Y-m-d H:i:s");
				$user_id 	= $_SESSION['user_session_id'];
				$id 		= $this->decryptData($data['token']);
				$state      = $this->getDetails(STATE_TBL,"name","id='".$data['state_id']."' ");
				$city       = $this->getDetails(LOCATIONGROUP_TBL,"group_name","id='".$data['city']."'");
				$location   = $this->getDetails(LOCATION_TBL,"location","id='".$data['location_area']."'");
				$check 		= $this->check_query(CUSTOMER_ADDRESS_TBL,"id","user_id= '".$user_id."' AND id='".$id."' AND delete_status='0' ");

				$check_token = $this->check_query(CUSTOMER_ADDRESS_TBL,"id"," token='".$data['name']."' AND id!='".$id."' ");
                if ($check_token==0) {
                      $token = $data['name'];
                }else{
                      $token = $data['name']."-".$this->generateRandomString("5");
                }

				$data = $this->cleanStringData($data);
				if($check){
					$query 	= "UPDATE ".CUSTOMER_ADDRESS_TBL." SET 
							token 			= '".$token."',
							user_name		= '".$data['name']."',
							mobile 			= '".$data['mobile']."',
							address 		= '".$data['address']."',
							landmark		= '".$data['landmark']."',
							city 			= '".$city['group_name']."',
							city_id 		= '".$data['city']."',
							state_id 		= '".$data['state_id']."',
							state_name 		= '".$state['name']."',
							pincode 		= '".$data['pincode']."',
							gst_name 		= '".$data['gst_name']."',
							gstin_number 	= '".$data['gstin_number']."',
							status			= '1',
							updated_at 		= '".$curr."'
							WHERE id 		= '".$id."' AND user_id='".$user_id."' ";
					$exe 	= $this->exeQuery($query);
					if($exe){
						unset($_SESSION['edit_shipping_address_key']);
						return 1;
					}else{
						return "Sorry!! Unexpected Error Occurred. Please try again.";
					}
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

	// Make Default Address

	function makeDefaultAddress($id='')
	{	
		$address_id 	= $this->decryptData($id);
		$curr  			= date("Y-m-d H:i:s");
		$user_id 		= $_SESSION['user_session_id'];
		$cart_id        = @$_SESSION['user_cart_id'];

		$q 	= "UPDATE ".CUSTOMER_ADDRESS_TBL." SET 
				default_address 	= '0',	
				updated_at 			= '".$curr."' 
				WHERE user_id 		= '".$user_id."' ";
		$result 		= $this->exeQuery($q);
		if ($result) {
			$q 	= "UPDATE ".CUSTOMER_ADDRESS_TBL." SET
				default_address 	= '1',
				updated_at	 		= '".$curr."' 
				WHERE id 			= '".$address_id."' AND user_id='".$user_id."'  ";
			$exe = $this->exeQuery($q);

			$query = "UPDATE ".CART_TBL." SET 
				delivery_id  = '".$address_id."', 
				shipping_id  = '".$address_id."',
				shipping_status = '1'
				WHERE id ='".$cart_id."' ";
			$exe = $this->exeQuery($query);
			if ($exe) {
				return 1;
			}else{
				return  "Unexpected Error Occurred";
			}
		}else{
			return  "Unexpected Error Occurred Try again";
		}
	}
	

	// Delete Address

	function deleteAddress($result)
	{	
		$layout  	= "";
		$address_id = $this->decryptData($result);
		$curr    	= date("Y-m-d H:i:s");
		$user_id 	= $_SESSION['user_session_id'];
        $cart_id 	= @$_SESSION['user_cart_id'];

		// Check atleast one shipping address

		$check_shp_add = $this->check_query(CUSTOMER_ADDRESS_TBL,"id"," user_id='".$user_id."' AND delete_status='0' AND status='1' ");


		if($check_shp_add > 1) {

			$query_delete = "UPDATE ".CUSTOMER_ADDRESS_TBL." SET
								delete_status = '1',
								updated_at    = '".$curr."' 
								WHERE id      = '".$address_id."' "; 
			$exe_delete   = $this->exeQuery($query_delete);

			if($exe_delete){

	   			// Make remainig one address should be default

				$last_address = $this->getDetails(CUSTOMER_ADDRESS_TBL,"*","user_id='".$user_id."' ORDER BY id DESC ");

				$query = "UPDATE ".CUSTOMER_ADDRESS_TBL." SET
							default_address = '1',
							updated_at      = '".$curr."' 
							WHERE id        = '".$last_address['id']."' "; 
				$exe   = $this->exeQuery($query);
	   
	   			// Make remainig one address should be default in cart

	            $query1 = "UPDATE ".CART_TBL." SET 
	                shipping_status = '1',
	                shipping_id     = '".$last_address['id']."',
	                delivery_id     = '".$last_address['id']."' 
	                WHERE id        ='".$cart_id."' ";
	            $exe1  = $this->exeQuery($query1);

				return "0`"."Address has been removed";
			}else{
				return "1`"."Sorry!! Unexpected Error Occurred. Please try again.";
			}	
		} else {
			return "2`"."User must have at least one shipping address.";
		}
	}

	// Get Return Product  Details

	function retunOrderProductInfo($data){
		$id = $this->decryptData($data);
		$query  = "SELECT O.sub_total,O.product_id,O.vendor_id,O.total_tax,O.qty,VE.company,P.product_name FROM  ".ORDER_ITEM_TBL." O LEFT JOIN ".VENDOR_TBL." VE ON (O.vendor_id=VE.id) LEFT JOIN ".PRODUCT_TBL." P ON (O.product_id=P.id)  WHERE O.id='".$id."' ";
		$exe 	= $this->exeQuery($query);
		return $this->editPagePublish(mysqli_fetch_assoc($exe));
	}

	// Get Return Reason list

    function getReturnReason($current="")
    {
        $layout = "";
        $q      = "SELECT * FROM ".RETURN_REASON_TBL." WHERE status='1' AND delete_status='0' " ;
        $query = $this->exeQuery($q);
        if(@mysqli_num_rows($query)>0){
            $i=0;
            while($list = mysqli_fetch_array($query)){
                $selected = (($list['id']==$current) ? 'selected' : '');
                $selected = (($selected=="selected")? $selected : (($list['id']==31) ? 'selected' : '') );
                $layout.= "<option value='".$list['id']."' $selected>".$list['return_reason']."</option>";
                $i++;
            }
        }
        return $layout;
    }

    function orderProductReturn($input)
    {	
    	if(isset($_SESSION['order_return_key'])){
			if($this->cleanString($input['fkey']) == $_SESSION['order_return_key']){
				$curr  		 	  = date("Y-m-d H:i:s");
				$data 		 	  = $this->cleanStringData($input);
				$order_item_id    = $this->decryptData($data['token']);

				$check_order_item = $this->check_query(ORDER_ITEM_TBL,"id","id='".$order_item_id."' ");
				if($check_order_item){
					$orderitem_info = $this->getDetails(ORDER_ITEM_TBL,"*","id='".$order_item_id."'  ");
					$order_info     = $this->getDetails(ORDER_TBL,"*","id='".$orderitem_info['order_id']."'  "); 
					$has_duration   = $this->getDetails(PRODUCT_TBL,"has_return_duration,return_duration","id='".$orderitem_info['product_id']."' AND has_return_duration='1' ");

					$return_condition = 0;

					$current_date  = date("Y-m-d H:i:s");
					$return_ends   = date("Y-m-d H:i:s");
					
					if($has_duration['has_return_duration']){
						$return_duration = $this->getDetails(RETURN_SETTINGS_TBL,"*","id='".$has_duration['return_duration']."' AND status='1' AND delete_status='0'  ");
						$days			 = $return_duration['days'] ?? '0';
						$hours			 = $return_duration['hours'] ?? '0';
						$minutes		 = $return_duration['minutes'] ?? '0';
						
						$currentDateTime = new DateTime($orderitem_info['delivery_date']);
						$currentDateTime->modify("+$days days");
						$currentDateTime->modify("+$hours hours");
						$currentDateTime->modify("+$minutes minutes");
						$return_ends 	 = $currentDateTime->format('Y-m-d H:i:s');
					}
					if ($return_ends > $current_date) {
	        			$return_condition = 1;
	        		}

					
					if($return_condition) {
						
						$query = "UPDATE ".ORDER_ITEM_TBL." SET 
									order_status  	= '3',
									return_status   = '1',
									return_reason 	= '".$data['return_reason']."',
									return_comment 	= '".$this->cleanString($data['return_remarks'])."',
									return_date 	= '".$curr."',
									updated_at 		= '".$curr."' WHERE id='".$order_item_id."' ";
						$exe = $this->exeQuery($query);

						$check_inprocess  = $this->check_query(ORDER_ITEM_TBL,"order_status","order_id='".$order_info['id']."' AND order_status='0' "); 
                    	$check_shipped    = $this->check_query(ORDER_ITEM_TBL,"order_status","order_id='".$order_info['id']."' AND order_status='1' "); 
                    	$check_delivered  = $this->check_query(ORDER_ITEM_TBL,"order_status","order_id='".$order_info['id']."' AND order_status='2' "); 
                    	$check_returned   = $this->check_query(ORDER_ITEM_TBL,"order_status","order_id='".$order_info['id']."' AND order_status='3' "); 

						if($check_inprocess==0 && $check_shipped==0 && $check_delivered==0 && $check_returned==1 ) {
					 		$query_rq = "UPDATE ".ORDER_TBL." SET 
								order_status  	= '3',
								return_status   = '1',
								return_reason 	= '".$data['return_reason']."',
								return_comment 	= '".$this->cleanString($data['return_remarks'])."',
								return_date 	= '".$curr."',
								updated_at 		= '".$curr."' WHERE id='".$order_info['id']."' ";
							$exe_rq = $this->exeQuery($query_rq);
						} else {
							$query_rq = "UPDATE ".ORDER_TBL." SET 
								return_status   = '1',
								updated_at 		= '".$curr."' WHERE id='".$order_info['id']."' ";
							$exe_rq = $this->exeQuery($query_rq);
						}

						$query_v = "UPDATE ".VENDOR_ORDER_ITEM_TBL." SET 
									order_status  	= '3',
									return_status   = '1',
									return_reason 	= '".$data['return_reason']."',
									return_comment 	= '".$this->cleanString($data['return_remarks'])."',
									return_date 	= '".$curr."',
									updated_at 		= '".$curr."' WHERE order_item_id='".$order_item_id."' ";
						$exe_v = $this->exeQuery($query_v);

						$vendor_order_info = $this->getDetails(VENDOR_ORDER_ITEM_TBL,"*","order_item_id='".$order_item_id."'");

						$ve_check_inprocess  = $this->check_query(VENDOR_ORDER_ITEM_TBL,"order_status","order_item_id='".$vendor_order_info['vendor_order_id']."' AND order_status='0' "); 
                    	$ve_check_shipped    = $this->check_query(VENDOR_ORDER_ITEM_TBL,"order_status","order_item_id='".$vendor_order_info['vendor_order_id']."' AND order_status='1' "); 
                    	$ve_check_delivered  = $this->check_query(VENDOR_ORDER_ITEM_TBL,"order_status","order_item_id='".$vendor_order_info['vendor_order_id']."' AND order_status='2' "); 
                    	$ve_check_returned   = $this->check_query(VENDOR_ORDER_ITEM_TBL,"order_status","order_item_id='".$vendor_order_info['vendor_order_id']."' AND order_status='3' ");


						if($ve_check_inprocess==0 && $ve_check_shipped==0 && $ve_check_delivered==0 && $ve_check_returned==1 ) {
							$query_rqv = "UPDATE ".VENDOR_ORDER_TBL." SET 
								order_status  	= '3',
								return_status   = '1',
								return_reason 	= '".$data['return_reason']."',
								return_comment 	= '".$this->cleanString($data['return_remarks'])."',
								return_date 	= '".$curr."',
								updated_at 		= '".$curr."' WHERE id='".$vendor_order_info['vendor_order_id']."' ";
							$exe_rqv = $this->exeQuery($query_rqv);
						} else {
							$query_rqv = "UPDATE ".VENDOR_ORDER_TBL." SET 
								return_status   = '1',
								updated_at 		= '".$curr."' WHERE id='".$vendor_order_info['vendor_order_id']."' ";
							$exe_rqv = $this->exeQuery($query_rqv);
						}
	
						if($exe){
							unset($_SESSION['order_return_key']);
							return 1;
						}else{
							return "Sorry!! Unexpected Error Occurred. Please try again.";
						}
					} else {
						return "Return period of 7 days from the delivery date is expired.";
					}
					
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

	// Manage wishlist

	function getProductWishList()
	{
		$layout = "";
		$q = "SELECT W.fav_status,W.vendor_id,W.variant_id,P.id,P.page_url,P.has_variants,P.sku,P.product_name,P.category_type,P.main_category_id,P.sub_category_id,P.delete_status,P.is_draft,P.status,C.category,C.page_url as cat_url,P.selling_price,P.actual_price,SC.subcategory,SC.page_url as sub_cat_url,P.display_tag,P.display_tag_end_date,DT.display_tag as display_tag_title,DT.status as tag_status,PV.variant_name,VE.company,VP.selling_price as v_selling_price ,(SELECT file_name FROM ".MEDIA_TBL." WHERE item_id=P.id AND item_type='product' AND delete_status=0 ORDER BY id ASC LIMIT 1) as product_image,
			(SELECT file_name FROM ".MEDIA_TBL." WHERE item_id=P.id AND item_type='product' AND delete_status=0 ORDER BY id DESC LIMIT 1) as secondary_img 
			FROM ".WISHLIST_TBL." W 
					LEFT JOIN ".PRODUCT_TBL." P ON(W.product_id=P.id)  
					LEFT JOIN ".MAIN_CATEGORY_TBL." C ON (C.id=P.main_category_id) 
					LEFT JOIN ".SUB_CATEGORY_TBL." SC ON (SC.id=P.sub_category_id) 
					LEFT JOIN ".VENDOR_TBL." VE ON (VE.id=W.vendor_id)
					LEFT JOIN ".PRODUCT_VARIANTS." PV ON (PV.id=W.variant_id)
					LEFT JOIN ".PRODUCT_DISPLAY_TAG." DT ON (P.display_tag=DT.id) 
					LEFT JOIN ".VENDOR_PRODUCTS_TBL." VP ON (VP.product_id=P.id AND VP.vendor_id=W.vendor_id AND VP.variant_id=W.variant_id) 
				WHERE  W.user_id='".@$_SESSION['user_session_id']."' ORDER BY W.id DESC " ;
	 	$exe = $this->exeQuery($q);
	 	if(mysqli_num_rows($exe)>0){
	 		    	$i=1;
	 		    	while($details = mysqli_fetch_array($exe)){
		    			$list  = $this->editPagePublish($details);

			            $wishlist_text 	 = (($list['fav_status']=="") ? "Add to wishlist" : "Remove from wishlist");

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



						$product_price_w = (($list['v_selling_price']!="")? $list['v_selling_price'] : $product_price['selling_price'] );


						if(isset($_SESSION['user_session_id'])) {
							$wishlist = "<li class='wishlist '><a href='".BASEPATH."login' class='addToWishList'   data-option='".$this->encryptData($list['id'])."' data-id='".$this->encryptData($list['id'])."' data-vendor_id='".$list['vendor_id']."' data-variant_id=".$list['variant_id']." title='".$wishlist_text."'><span class='far fa-heart'></span><i class='fas fa-heart d-none'></i></a></li>";

						}else{
							$wishlist = "<li class='wishlist'><a href='".BASEPATH."login' title='Login to add Wishlist'><span class='far fa-heart'></span><i class='fas fa-heart d-none'></i></a></li>";
						}

						$variant_name = (($list['variant_id'])?   "-".$list['variant_name']   :   ""   );
						$vendor_name  = (($list['vendor_id'])?   "Sold By : ".$list['company']   :  "Sapiens"   );

			            $product_price = $this->getProductPrice($list['id'],$variant);

			            $product_image = $list['product_image']!='' ? SRCIMG.$list['product_image'] : ASSETS_PATH."no_img.jpg" ;

			            $secondary_image   = $list['secondary_img']!='' ? SRCIMG.$list['secondary_img'] : ASSETS_PATH."no_img.jpg" ;

			            $product_category = $list['category_type']=="main" ? "<a href='".BASEPATH."product/category/".$list['cat_url']."'>".$list['category']."</a>" : "<a href='".BASEPATH."product/subcategory/".$list['sub_cat_url']."'>".$list['subcategory']."</a>" ;
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
	 	return $layout;
		
	}


	/*--------------------------------------------- 
				My Orders
	----------------------------------------------*/

	// Myorders Pagination Count

	function myordersPaginationCount($page_amount,$search_order_key="",$search_date="")
	{
		$layout ="";

		$order_ids = $this->getSearchKeyProductOrderIds($search_order_key);

		if($search_order_key!="") {

			if($order_ids!="") {
				$search_condition = " AND (O.order_uid LIKE '%".$search_order_key."%' OR O.id IN (".$order_ids.")  )  ";
			} else {
				$search_condition = " AND O.order_uid LIKE '%".$search_order_key."%'  ";					
			}

		} else {
			$search_condition = "";		
		}

		if($search_date['from']!="") {
				$search_condition = "AND DATE(O.order_date)='".$search_date['from']."' ";		
			if($search_date['to']!="") {
				$search_condition = "AND DATE(O.order_date) BETWEEN '".$search_date['from']."' AND '".$search_date['to']."' ";		
			}
		} 

		$sql    = "SELECT O.id,O.total_amount,O.sub_total,O.igst_amt,O.order_address,O.shipping_cost,O.coupon_value,O.order_uid,O.payment_type,O.payment_status,O.order_date,O.cancel_status,O.shipping_status,O.deliver_status,O.replaced_order,O.created_at,OA.area_name FROM ".ORDER_TBL." O LEFT JOIN ".CUSTOMER_ADDRESS_TBL." OA ON (OA.id=O.order_address) WHERE O.user_id='".@$_SESSION['user_session_id']."' $search_condition AND replaced_order='0' ORDER BY O.id  DESC ";
		$rs_result = $this->exeQuery($sql); //run the query
		$total_records = mysqli_num_rows($rs_result);  //count number of records
		$num_rec_per_page = (($page_amount=="show_all")? mysqli_num_rows($rs_result) : $page_amount );
		$total_pages = ceil($total_records / $num_rec_per_page);
		return $total_pages;
	}

	// Myorders Pagination

	function myordersPagination($current="",$page_link,$page_amount,$search_order_key="",$search_date="")
	{
		$layout    ="";
		$condition = "";

		$order_ids = $this->getSearchKeyProductOrderIds($search_order_key);

		if($search_order_key!="") {

			if($order_ids!="") {
				$search_condition = " AND (O.order_uid LIKE '%".$search_order_key."%' OR O.id IN (".$order_ids.")  )  ";
			} else {
				$search_condition = " AND O.order_uid LIKE '%".$search_order_key."%'  ";					
			}

		} else {
			$search_condition = "";		
		}

		if($search_date['from']!="") {
				$search_condition = "AND DATE(O.order_date)='".$search_date['from']."' ";		
			if($search_date['to']!="") {
				$search_condition = "AND DATE(O.order_date) BETWEEN '".$search_date['from']."' AND '".$search_date['to']."' ";		
			}
		} 

		$sql = "SELECT O.id,O.total_amount,O.sub_total,O.igst_amt,O.order_address,O.shipping_cost,O.coupon_value,O.order_uid,O.payment_type,O.payment_status,O.order_date,O.cancel_status,O.shipping_status,O.deliver_status,O.replaced_order,O.created_at,OA.area_name FROM ".ORDER_TBL." O LEFT JOIN ".CUSTOMER_ADDRESS_TBL." OA ON (OA.id=O.order_address) WHERE O.user_id='".@$_SESSION['user_session_id']."' $search_condition AND replaced_order='0'  ORDER BY O.id  DESC "; 
		$rs_result = $this->exeQuery($sql); //run the query
		$total_records = mysqli_num_rows($rs_result);  //count number of records
		$total_pages = ceil($total_records / (($page_amount=="show_all")? mysqli_num_rows($rs_result) : $page_amount ));
		$page = $current;
		$limit= 6;
		if ($total_pages >=1 && $page <= $total_pages){
			$counter = 1;
			$link = "";
			$dot_link = "<li><a href='javascript:void();' >...</a></li>";
			if ($page >  ($limit/2)){
				$layout .= "<li><a href='".$page_link."p=1' >1</a></li>";
			}
			for ($i=$page; $i<=$total_pages;$i++){
				$current_page = (($i==$current) ? "current" : "");
	            if($counter < $limit) {
	            	$layout .= "<li class='".$current_page."'><a href=".$page_link."p=".$i.">".$i."</a></li>";
	            }
	            $counter++;
	        }
	        if ($page < $total_pages - ($limit/2)){
	        	$current_page = (($i==$current) ? "current" : "");
	        	$layout .= $dot_link."<li class='".$current_page."'><a href='".$page_link."p=".($i-1)."'>".($i-1)."</a></li>"; 
	        }
		}
		return $layout;
	}

	// Manage order List

	function manageMyOrders($page="",$page_amount,$search_order_key="",$search_date="")
	{	
		$layout = "";

		$order_ids = $this->getSearchKeyProductOrderIds($search_order_key);
		
		if($search_order_key!="") {

			if($order_ids!="") {
				$search_condition = " AND (O.order_uid LIKE '%".$search_order_key."%' OR O.id IN (".$order_ids.")  )  ";
			} else {
				$search_condition = " AND O.order_uid LIKE '%".$search_order_key."%'  ";					
			}

		} else {
			$search_condition = "";		
		}

		if($search_date['from']!="") {
				$search_condition = "AND DATE(O.order_date)='".$search_date['from']."' ";		
			if($search_date['to']!="") {
				$search_condition = "AND DATE(O.order_date) BETWEEN '".$search_date['from']."' AND '".$search_date['to']."' ";		
			}
		} 
		
		$query = "SELECT O.id,O.total_amount,O.sub_total,O.igst_amt,O.order_address,O.shipping_cost,O.coupon_value,O.order_uid,O.payment_type,O.payment_status,O.order_date,O.cancel_status,O.shipping_status,O.deliver_status,O.replaced_order,O.created_at,OA.area_name FROM ".ORDER_TBL." O LEFT JOIN ".CUSTOMER_ADDRESS_TBL." OA ON (OA.id=O.order_address) WHERE O.user_id='".@$_SESSION['user_session_id']."' $order_replace_check $search_condition AND replaced_order='0'  ORDER BY O.id  DESC";

		if($page_amount!="show_all") {
    		$start_from = ($page-1)*$page_amount;
  			$page_count = $page_amount;
       		$query .= "  LIMIT $start_from, $page_count";
    	} else {
    		$start_from = 0;
    	}

		$exe = $this->exeQuery($query);
		$total_orders = mysqli_num_rows($exe);
	 	if(mysqli_num_rows($exe)>0){
			$i = 1;
			while ($details = mysqli_fetch_array($exe)) {
		    			$list  = $this->editPagePublish($details);

				if ($list['cancel_status']==0) {
		    		if ($list['shipping_status']==1 && $list['deliver_status']==0) {
			    		$payment = "<em>Shipped</em> ";
				    }elseif ($list['deliver_status']==1) {
				    	$payment = "<em>Delivered</em>";
				    }else{
				    	$payment = "<em>Processing</em>";
				    }
		    	}else{
		    		$payment = "<em>Cancelled</em>";
		    	}


		    	if($list['coupon_value']!="" && $list['coupon_value']!=NUll) {
		    		$coupon_value = $list['coupon_value'];
		    	} else {
		    		$coupon_value = 0;
		    	}

				$total = $list['sub_total'] + $list['igst_amt'] + $list['shipping_cost'] - $coupon_value;

		  //   	if($list['replaced_order']==1) {

				// 	$total = $this->getDetails(ORDER_ITEM_TBL," SUM(sub_total) + SUM(igst_amt) as new_total "," id='".$order_item_id."' " );
				// 	$total = $total['new_total'];
				// }

				//$delivery = $list['delivery_type']=="home" ? ucwords($list['delivery_type']	). " Delivery" : $this->unHyphenize($list['delivery_type']);
				$layout .="
					<div class='card mt-3'>
						<div class='card-header my_order_headder_color'>
						  	<div class='row d-flex justify-content-between align-items-center'>
							  	<div class='col-md-6'>
							  		<span><b>Order No.".$list['order_uid']."</b></span>
							  		<span class='ord_date'>".date('d  F  Y h:i a',strtotime($list['created_at']))."</span>
							  	</div>
							  	<div class='col-md-6'>
								  <div class='row'>
								  		<div class='col-lg-12'>
								  			<span class='total_amt d-inline-block float-end' ><i class='fas fa-map-marker-alt me-2'></i>".ucfirst($list['area_name'])."</span>
								  		</div>
								  		<div class='col-lg-12'>
							  				<span class='total_amt float-end' >₹ ".number_format($total,2)."</span>
							  			</div>
							  		</div>
							  	</div>
						  	</div>
						</div>
						".$this->orderItems($list['id'])."
					</div>
					";
			$i++;
			}
 		} else {
 			$layout = "No Records Found";
 		}
 		
 		$result = array();
		$result['layout'] 	  = $layout;
		$result['start_from'] = (mysqli_num_rows($exe)!=0)? $start_from + 1 : 0;
		$result['start_to']   = $start_from + mysqli_num_rows($exe);
		$result['totals']     = $total_orders ;
		return $result;
	}

	// Get order ids for search key matched product 

	function getSearchKeyProductOrderIds($search_order_key)
	{	
		$product_ids   = array();
		$product_query = "SELECT id FROM ".PRODUCT_TBL." WHERE product_name LIKE '%".$search_order_key."%' AND delete_status='0' AND is_draft='0' AND status='1' ";
		$product_exe   = $this->exeQuery($product_query);
		
		if(mysqli_num_rows($product_exe) > 0) {
			while ($list       = mysqli_fetch_assoc($product_exe) ) {
				$product_ids[] = $list['id']; 
			}
		}

		$product_ids = ((count($product_ids) > 0)? implode(",", $product_ids) : 0 );
		$order_ids   = array();
		$query 		 = "SELECT id,order_id FROM ".ORDER_ITEM_TBL." WHERE user_id='".@$_SESSION['user_session_id']."' AND product_id IN (".$product_ids.") ";
		$exe         = $this->exeQuery($query);
 
		if(mysqli_num_rows($exe) > 0 ) {
			while ($list     = mysqli_fetch_assoc($exe)) {
				$order_ids[] = $list['order_id'];
			}
		}

		$order_ids = ((count($order_ids) > 0)? implode(",", array_unique($order_ids)) : "" );

		return $order_ids;
	}

	function orderItems($order_id) 
	{	
		$layout 	="";

		$query  	="SELECT O.id,O.qty,O.user_id,O.tax_amt,O.vendor_id,O.variant_id,O.vendor_invoice_number,O.vendor_id,O.order_status,O.cart_id,O.product_id,O.coupon_value,O.variant_id,O.order_id,O.category_id,O.sub_category_id,O.price,O.tax_amt,O.tax_type,O.final_price,O.qty,O.sub_total,O.vendor_commission_tax_amt,O.vendor_payment_tax_amt,O.vendor_shipping_tax_amt,O.vendor_commission_tax,O.vendor_payment_tax,O.vendor_shipping_tax,O.total_tax,O.sgst,O.cgst,O.igst,O.sgst_amt,O.cgst_amt,O.igst_amt,O.vendor_payment_charge,O.vendor_payment_charge_amt,O.vendor_shipping_charge,O.vendor_shipping_charge_amt,O.total_amount,O.vendor_commission,O.vendor_commission_amt,O.shipping_remarks,O.delivery_date,O.return_status,O.deliver_status,O.delivery_remarks,O.cancel_comment,O.vendor_response,O.replaced_order,O.replace_order_item_id,O.vendor_accept_status,O.response_notes,O.cancel_status,O.replace_order_id,P.product_uid,P.product_name,P.page_url,P.category_type,P.main_category_id,P.sub_category_id,P.has_return_duration,P.return_duration,V.variant_name,OD.order_uid,OD.shipping_cost,OD.order_address,OD.created_at,C.name,C.mobile,C.email,O.vendor_commission_amt as total_commission,O.vendor_payment_charge_amt as total_payment_charge_amt,O.vendor_shipping_charge_amt as total_shipping_charge_amt,O.total_amount as vendor_total_amt,
				(SELECT file_name FROM ".MEDIA_TBL." WHERE item_id=P.id AND item_type='product' AND delete_status=0 ORDER BY id ASC LIMIT 1) as product_image, 
	    		(SELECT file_name FROM ".MEDIA_TBL." WHERE item_id=P.id AND item_type='product' AND delete_status=0 AND is_default=1 ORDER BY id ASC LIMIT 1) as default_product_image
			FROM ".ORDER_ITEM_TBL." O LEFT JOIN ".PRODUCT_TBL." P ON (P.id=O.product_id) LEFT JOIN ".PRODUCT_VARIANTS." V ON (O.variant_id=V.id) LEFT JOIN ".ORDER_TBL." OD ON (O.order_id=OD.id) LEFT JOIN ".CUSTOMER_TBL." C ON(O.user_id=C.id) WHERE O.order_id=$order_id AND  O.user_id='".@$_SESSION['user_session_id']."'  ORDER BY O.price*O.qty  DESC";

		$exe 	= $this->exeQuery($query);
		$prd_count = mysqli_num_rows($exe);
		if(mysqli_num_rows($exe) > 0) {
				$i = 1;
				while ($details = mysqli_fetch_array($exe)) {

		    			$list  = $this->editPagePublish($details);

						$info  	= $this->getDetails(VENDOR_TBL,"company","id='".$list['vendor_id']."' ");

		    			if ($list['default_product_image']!="") {
							$product_image = $list['default_product_image']!='' ? SRCIMG.$list['default_product_image'] : ASSETS_PATH."no_img.jpg" ;# code...
						}else{
							$product_image = $list['product_image']!='' ? SRCIMG.$list['product_image'] : ASSETS_PATH."no_img.jpg" ;
						}

						$name             = $list['variant_id']==0 ? $list['product_name'] : $list['product_name']." - ".$list['variant_name'] ;

						if($list['category_type']=="main")
			        	{
			        		$cat  		= $this->getDetails(MAIN_CATEGORY_TBL,"id,category"," id='".$list['main_category_id']."' ");
			        		$category 	= $cat['category'];
			        	} else {
			        		$cat  		= $this->getDetails(SUB_CATEGORY_TBL,"id,subcategory"," id='".$list['sub_category_id']."' ");
			        		$category 	= $cat['subcategory'];
			        	}

			        	$return_button = "<span class='vendor_waiting_msg prd_info'>Order has been placed</span>";

			        	if($list['order_status']==1) {
		        			$return_button = "<span class='vendor_waiting_msg prd_info'>Order is out for delivery</span>";
			        	}
			        	if($list['order_status']==2) {
		        			$return_button = "<span class='vendor_accept_msg prd_info'>Order has been delivered</span>";
			        	}

			        	if($list['vendor_response']==1 && $list['vendor_accept_status']==0 && $list['order_status']==0) {
				        		$return_button = "Vendor has rejected this order";
			        		if($list['cancel_status']==0 && $list['replace_order_id']==0) {
			        			$return_button = $return_button." and waiting for admin response </span>";
			        		} elseif ($list['cancel_status']==1 && $list['replace_order_id']==0) {
			        			$return_button = "Vendor and admin both rejected this order";
			        		} elseif ($list['cancel_status']==0 && $list['replace_order_id']!=0) {
			        			$return_button = "Admin has replaced the order to another vendor";
			        		}
			        		$return_button = "<span class='vendor_reject_msg prd_info'>".$return_button."</span>";
			        	} elseif($list['vendor_response']==1 && $list['vendor_accept_status']==1 && $list['order_status']==0) {
				        	$return_button = "<span class='vendor_accept_msg prd_info'>Vendor accepted your order</span>";
			        	} elseif($list['vendor_response']==0 && $list['vendor_accept_status']==0 && $list['order_status']==0) {
			        		$return_button = "<span class='vendor_waiting_msg prd_info'>Waiting for vendor confirmation</span>";
			        	} elseif ($list['return_status']==1) {
			        		$return_button = "<span class='return_request_msg prd_info'>Return request is being process</span>";
				        } elseif ($list['order_status']==2) {
			        		$return_button = "<span class='vendor_accept_msg prd_info'>Order has been delivered</span>";
				        } 

				        // Check if this is replaced order item

				        $replace_check = $this->check_query(ORDER_ITEM_TBL,"id"," replace_order_id='".$list['order_id']."' AND replace_order_item_id='".$list['id']."'   " );


				        if($replace_check) {
				        	$replace_info = $this->getDetails(ORDER_ITEM_TBL,"id,order_id"," replace_order_id='".$list['order_id']."' AND replace_order_item_id='".$list['id']."'   " );
				        	$view_details_btn = "<a class='btn theme-btn-color-outline btn-sm float-end' href='".BASEPATH."myaccount/orderitem/".$replace_info['order_id']."' >View Details</a>";
				        } else {
				        	$view_details_btn = "<a class='btn theme-btn-color-outline btn-sm float-end' href='".BASEPATH."myaccount/orderitem/".$list['order_id']."' >View Details</a>";
				        }


				        if($list['replace_order_id']!=0) {
		    				$layout .= $this->orderItems($list["replace_order_id"],$list['replace_order_item_id']) ;
		    			} else {
		    				$layout .="<div class='card-body myorder_cart_body'>
										<div class='row'>
											<div class='col-md-2'>
										       <img src='".$product_image."' width=80 />
										    </div>
										    <div class='col-md-6'>
										      <span class='prd_name'>".$name."</span>
										      <span class='prd_info'>Category : ".$category."</span>
										      <span class='prd_info'>Sold By : ".$info['company']."</span>
										      <span class='prd_info'>Quantity : ".$list['qty']."</span>
										    </div>
										    <div class='col-md-4'>
										     	".$view_details_btn."
										     	<span class='float-end mt-4 pt-3'>".$return_button."</span>
										    </div>
										    <div></div>
									    </div>
									</div>";
		    			}

						
						if($i<$prd_count) {			
							$layout .="<hr>";
						}
						$i++;
			}
		}
		return $layout;
	}

	function manageMyOrderItem($order_id,$hide_address_info="")
	{	
		$layout = "";
		
		$query = "SELECT O.id,O.total_amount,O.sub_total,O.igst_amt,O.order_address,O.shipping_cost,O.coupon_value,O.order_uid,O.payment_type,O.payment_status,O.order_date,O.cancel_status,O.shipping_status,O.replaced_order,O.deliver_status,O.created_at FROM ".ORDER_TBL." O WHERE O.user_id='".@$_SESSION['user_session_id']."' AND O.id='".$order_id."' ORDER BY O.id  DESC";
		$exe = $this->exeQuery($query);
	 	if(mysqli_num_rows($exe)>0){
			$i = 1;
			while ($details = mysqli_fetch_array($exe)) {
		    			$list  = $this->editPagePublish($details);

				if ($list['cancel_status']==0) {
		    		if ($list['shipping_status']==1 && $list['deliver_status']==0) {
			    		$payment = "<em>Shipped</em> ";
				    }elseif ($list['deliver_status']==1) {
				    	$payment = "<em>Delivered</em>";
				    }else{
				    	$payment = "<em>Processing</em>";
				    }
		    	}else{
		    		$payment = "<em>Cancelled</em>";
		    	}

				$address_info  	= $this->getDetails(CUSTOMER_ADDRESS_TBL,"*","id='".$list['order_address']."' ");

				//$delivery = $list['delivery_type']=="home" ? ucwords($list['delivery_type']	). " Delivery" : $this->unHyphenize($list['delivery_type']);


				if($list['coupon_value']!="" && $list['coupon_value']!=NUll) {
		    		$coupon_value = $list['coupon_value'];
		    	} else {
		    		$coupon_value = 0;
		    	}

				$total = $list['sub_total'] + $list['igst_amt'] + $list['shipping_cost'] - $coupon_value ;


				if($hide_address_info=="") {

					$address_info = "<div class='card address_card'>
									  	<div class='card-body'>
										    <div class='row'>
										    	<div class='col-md-8'>
													<div  >
														<span class='prd_name'>Delivery Address</span>
														<span class='prd_name'>".$address_info['user_name']."</span>
														<span >".$address_info['address'].",</span>
														<span >".$address_info['landmark'].",</span>
														<span >".$address_info['area_name'].",</span>
														<span >".$address_info['city']."-".$address_info['pincode'].",</span>
														<span >Mobile : ".$address_info['mobile']."</span>
													</div>
										    	</div>
										    	<div class='col-md-4'>
													<div >
														<span class='prd_name'>Payment Summary</span>
														<span >Price 		: ₹ ".number_format($total + $coupon_value,2)."</span>
														<span >Shipping Fee : ₹ ".number_format((int)$list['shipping_cost'],2)."</span>
														<span >Discount		: ₹ ".number_format($coupon_value,2)."</span>
														<span class='prd_name' >Order Total  :  ₹ ".number_format($total,2)."</span>
													</div>
										    	</div>
										    </div>
									  	</div>
									</div>";
				} else {
					$address_info  = "";
				}

				$layout .="
						<div class='card mt-3'>
							<div class='card-header my_order_headder_color'>
								<div class='row d-flex justify-content-between align-items-center'>
									<div class='col-md-6'>
										<span><b>Order No.".$list['order_uid']."</b></span>
										<span class='ord_date'>".date('d  F  Y h:i a',strtotime($list['created_at']))."</span>
									</div>
									<div class='col-md-6'>
										<span  class='total_amt float-end' >₹  ".number_format($total,2)."</span>
									</div>
								</div>
							</div>
								".$this->getMyOrderItems($list['id'])."
						</div>
						".$address_info."
					";
			$i++;
			}
 		}else{
 			return "order items are empty..";
 		}
 		return $layout;
	}

	function getMyOrderItems($order_id) 
	{	
		$layout 	="";

		$query  	= "SELECT O.id,O.qty,O.user_id,O.tax_amt,O.vendor_id,O.variant_id,O.vendor_invoice_number,O.vendor_id,O.order_status,O.cart_id,O.product_id,O.coupon_value,O.variant_id,O.order_id,O.category_id,O.sub_category_id,O.price,O.tax_amt,O.tax_type,O.final_price,O.qty,O.sub_total,O.vendor_commission_tax_amt,O.vendor_payment_tax_amt,O.vendor_shipping_tax_amt,O.vendor_commission_tax,O.vendor_payment_tax,O.vendor_shipping_tax,O.total_tax,O.sgst,O.cgst,O.igst,O.sgst_amt,O.cgst_amt,O.igst_amt,O.vendor_payment_charge,O.vendor_payment_charge_amt,O.vendor_shipping_charge,O.vendor_shipping_charge_amt,O.total_amount,O.vendor_commission,O.vendor_commission_amt,O.shipping_remarks,O.delivery_date,O.return_status,O.deliver_status,O.delivery_remarks,O.cancel_status,O.cancel_comment,O.vendor_response,O.replace_order_id,O.replace_order_item_id,O.vendor_accept_status,O.response_notes,O.created_at as item_created_at,O.updated_at as item_updated_at,P.product_uid,P.product_name,P.page_url,P.category_type,P.main_category_id,P.sub_category_id,P.has_return_duration,P.return_duration,V.variant_name,OD.order_uid,OD.shipping_cost,OD.order_address,OD.created_at,C.name,C.mobile,C.email,O.vendor_commission_amt as total_commission,O.vendor_payment_charge_amt as total_payment_charge_amt,O.vendor_shipping_charge_amt as total_shipping_charge_amt,O.total_amount as vendor_total_amt,RS.response_status as response_status_text,
				(SELECT file_name FROM ".MEDIA_TBL." WHERE item_id=P.id AND item_type='product' AND delete_status=0 ORDER BY id ASC LIMIT 1) as product_image, 
	    		(SELECT file_name FROM ".MEDIA_TBL." WHERE item_id=P.id AND item_type='product' AND delete_status=0 AND is_default=1 ORDER BY id ASC LIMIT 1) as default_product_image
			FROM ".ORDER_ITEM_TBL." O LEFT JOIN ".PRODUCT_TBL." P ON (P.id=O.product_id) 
									  LEFT JOIN ".PRODUCT_VARIANTS." V ON (O.variant_id=V.id) 
									  LEFT JOIN ".ORDER_TBL." OD ON (O.order_id=OD.id) 
									  LEFT JOIN ".CUSTOMER_TBL." C ON(O.user_id=C.id) 
									  LEFT JOIN ".ORDER_RESPONSE_STATUS_TBL." RS ON(O.response_status=RS.id) 
			WHERE O.order_id=$order_id AND  O.user_id='".@$_SESSION['user_session_id']."' ORDER BY O.price*O.qty  DESC";
		$exe 	= $this->exeQuery($query);
		$prd_count = mysqli_num_rows($exe);
		if(mysqli_num_rows($exe) > 0) {
				$i = 1;
				while ($details = mysqli_fetch_array($exe)) {
		    			$list  			= $this->editPagePublish($details);
						$info  			= $this->getDetails(VENDOR_TBL,"company","id='".$list['vendor_id']."' ");
						$address_info  	= $this->getDetails(CUSTOMER_ADDRESS_TBL,"*","id='".$list['order_address']."' ");

		    			if($list['default_product_image']!="") {
							$product_image = $list['default_product_image']!='' ? SRCIMG.$list['default_product_image'] : ASSETS_PATH."no_img.jpg" ;# code...
						} else {
							$product_image = $list['product_image']!='' ? SRCIMG.$list['product_image'] : ASSETS_PATH."no_img.jpg" ;
						}

						$name             = $list['variant_id']==0 ? $list['product_name'] : $list['product_name']." - ".$list['variant_name'] ;

						if($list['category_type']=="main")
			        	{
			        		$cat  		= $this->getDetails(MAIN_CATEGORY_TBL,"id,category"," id='".$list['main_category_id']."' ");
			        		$category 	= $cat['category'];
			        	} else {
			        		$cat  		= $this->getDetails(SUB_CATEGORY_TBL,"id,subcategory"," id='".$list['sub_category_id']."' ");
			        		$category 	= $cat['subcategory'];
			        	}

			        	$packed  	= "todo";
		        		$shipped 	= "todo";
		        		$delivered 	= "todo";
		        		$order_status_text = "Your Order has been placed";
		        		$sub_ord_sts_txt = "to";
		        		$status_time_date = $list['created_at'];

			        	if($list['order_status']==1) {
			        		$packed  = "done";
			        		$shipped = "done";
		        			$order_status_text = "Your Order is out for delivery";
		        			$sub_ord_sts_txt = "from";
		        			$status_time_date = $list['item_updated_at'];
			        	}
			        	if($list['order_status']==2) {
			        		$packed  = "done";
			        		$shipped =  "done";
			        		$delivered = "done";
		        			$order_status_text = "Your Order has been delivered";
		        			$sub_ord_sts_txt = "From";
		        			$status_time_date = $list['item_updated_at'];
			        	}

			        	if($list['return_status']==1) {
			        		$packed  = "done";
			        		$shipped =  "done";
			        		$delivered = "done";
		        			$order_status_text = "Your Order has been delivered";
		        			$sub_ord_sts_txt = "to";
		        			$status_time_date = $list['item_updated_at'];
			        	}

			        	$return_condition = 0;
			        	$return_button    = "";

			        	if($list['order_status']!=0) {
			        		$item_content  = "	<div class='col-lg-9 col-md-12 col-sm-12 date_in_prog'>
													<ol class='progtrckr' data-progtrckr-steps='4'>
														<li class='progtrckr-done'>Ordered</li><!--
														--><li class='progtrckr-".$packed."'>Packed</li><!--
														--><li class='progtrckr-".$shipped."'>Shipped</li><!--
														--><li class='progtrckr-".$delivered."'>Delivered</li>
													</ol>
													<div><span>".date('D, dS M',strtotime($list['created_at']))."</span></div>
												</div>";
			        	}

			        	if($list['vendor_response']==1 && $list['vendor_accept_status']==0 && $list['order_status']==0) {
				        	$return_button = "<span class='vendor_reject_msg'>Vendor has rejected this order</span>";

				        	$item_content  = "	<div class='col-md-9 col-sm-12'>
													<div class='reject_reason_msg'>
														<h5 class='card-title'>Rejected Reason</h5>
														<h5 class='reject_reason_msg date' > ".$list['response_status_text']."</h5></div>
												</div>";
							$invoice_details = "
												<span ><i class='fas fa-info-circle' aria-hidden='true'></i> <a href='".BASEPATH."pages/details/privacy-ploicy' target='blank'> View Privacy Policy </a>
												</span>
												<span ><i class='fas fa-address-book' aria-hidden='true'></i> <a href='".BASEPATH."contact'> Contact us </a>
												</span>";
			        	} elseif($list['vendor_response']==1 && $list['vendor_accept_status']==1 && $list['order_status']==0) {
				        	$return_button = "<span class='vendor_accept_msg'>Vendor has been accepted your order</span>";
			        		$item_content  = "	<div class='col-lg-9 col-md-12 col-sm-12 date_in_prog'>
													<ol class='progtrckr' data-progtrckr-steps='4'>
														<li class='progtrckr-done'>Ordered</li><!--
														--><li class='progtrckr-".$packed."'>Packed</li><!--
														--><li class='progtrckr-".$shipped."'>Shipped</li><!--
														--><li class='progtrckr-".$delivered."'>Delivered</li>
													</ol>
													<div><span>".date('D, dS M',strtotime($list['created_at']))."</span></div>
												</div>";
			        	} elseif($list['vendor_response']==0 && $list['vendor_accept_status']==0 && $list['order_status']==0) {
			        		$return_button = "<span class='vendor_waiting_msg'>Waiting for vendor order confirmation</span>";

				        	$item_content  = "	<div class='col-md-9'>
													<div class='reject_reason_msg'>
													</div>
												</div>";
			        	}


			        	if($list['vendor_response']==1 && $list['vendor_accept_status']==0 && $list['order_status']==0) {
				        		$return_button = "Vendor has rejected this order";
			        		if($list['cancel_status']==0 && $list['replace_order_id']==0) {
			        			$return_button = $return_button." and waiting for admin response </span>";
			        		} elseif ($list['cancel_status']==1 && $list['replace_order_id']==0) {
			        			$return_button = "Vendor and admin both rejected this order";
			        		} elseif ($list['cancel_status']==0 && $list['replace_order_id']!=0) {
								$return_button = "Admin has replaced the order with following vendor details";
			        		}
			        		$return_button = "<span class='vendor_reject_msg prd_info'>".$return_button."</span>";
			        	} elseif($list['vendor_response']==1 && $list['vendor_accept_status']==1 && $list['order_status']==0) {
				        	$return_button = "<span class='vendor_accept_msg prd_info'>Vendor accepted your order</span>";
			        	} elseif($list['vendor_response']==0 && $list['vendor_accept_status']==0 && $list['order_status']==0) {
			        		$return_button = "<span class='vendor_waiting_msg prd_info'>Waiting for vendor confirmation</span>";
			        	} elseif ($list['return_status']==1) {
			        		$return_button = "<span class='return_request_msg prd_info'>Return request is being process</span>";
				        } elseif ($list['order_status']==2) {
			        		$return_button = "<span class='vendor_accept_msg prd_info'>Order has been delivered</span>";
				        } 

			        	if($list['vendor_accept_status']==1) {
			        		$invoice_details = "<span ><i class='fas fa-download' aria-hidden='true'></i> 
			        								<a href='".BASEPATH."myaccount/downloadInvoice/".$list['id']."'> Download Invoice </a>
			        							</span>
												<span ><i class='fas fa-eye' aria-hidden='true'></i> 
													<a href='".BASEPATH."myaccount/previewinvoice/".$list['id']."' target='blank'> Preview Invoice </a>
												</span>
												<span ><i class='fas fa-info-circle' aria-hidden='true'></i> <a href='".BASEPATH."pages/details/return-policy' target='blank'> View Return Policy </a></span>
													<span ><i class='fas fa-address-book' aria-hidden='true'></i> <a href='".BASEPATH."contact'> Contact us </a></span>";
			        	} else {
			        		$invoice_details = "";
			        	}

				        if ($list['has_return_duration']==1) {

				        	if($list['deliver_status']==1 && $list['return_status']!=1) {
								$current_date  = date("Y-m-d H:i:s");
								$return_duration = $this->getDetails(RETURN_SETTINGS_TBL,"*","id='".$list['return_duration']."' ");



								if($return_duration['return_setting']=='days') {
									$return_ends   = date("Y-m-d H:i:s",strtotime("+".$return_duration['days']." days",strtotime($list['delivery_date'])));
								} else {
									if($return_duration['minutes']=="" || $return_duration['minutes']==0  ) {
										$return_ends   = date('Y-m-d H:i:s',strtotime('+'.$return_duration['hours'].' hour',strtotime($list['delivery_date'])));
									} elseif($return_duration['hours']==0) {
										$return_ends   = date('Y-m-d H:i:s',strtotime('+'.$return_duration['minutes'].' minutes',strtotime($list['delivery_date'])));
									} else {
										$return_ends   = date('Y-m-d H:i:s',strtotime('+'.$return_duration['hours'].' hour +'.$return_duration['minutes'].' minutes',strtotime($list['delivery_date'])));
									}

								}

								if ($return_ends > $current_date) {
				        			$return_condition = 1;
				        		}

				        	} elseif ($list['return_status']==1) {
				        		$return_button = "<span class='return_request_msg'>Your return request is being process</span>";
				        	}
				        }

			        	if($return_condition==1)
			        	{
			        		$return_button = "<a class='btn btn-outline-danger btn-sm return_order float-end' data-order_item='".$this->encryptData($list['id'])."' data-order_item_nor='".$list['id']."' href='javascript:void();' data-toggle='modal' data-target='#return_form' >Return</a>";
			        	} 


			        	$shipping = (int)$list['shipping_cost'];

			        	$total = $list['sub_total'] +  $list['total_tax'];

			        	if($list['replace_order_id']!=0) {
			        		$hide_address_info  = 1;
				        	$replace_order_info = $this->manageMyOrderItem($list['replace_order_id'],$list['replace_order_item_id'],$hide_address_info);
				        	$reviewe_laouyt = "";

				        } else {
				        	$replace_order_info = "";
				        	
					        if($list['order_status']==2 && $list['deliver_status']==1) {

				        		$reviewe_laouyt  = 
				        			"<div class='reviewsection' id='reviews'>
										".$this->getCustomerReviewAndRatings($list['product_id'],$list['vendor_id'],$list['order_id'])."
							        </div>";

						    } else {
						    	$reviewe_laouyt = "";
						    }

 
				        }

						$layout .="	<div class='card-body'>
										<div class='row'>
											<div class='col-md-2'>
												<a href='".BASEPATH."product/details/".$list['page_url']."'><img src='".$product_image."' width=80 /></a>
											</div>
											<div class='col-md-8'>
												<span class='prd_name'><a href='".BASEPATH."product/details/".$list['page_url']."'>".$name."</a></span>
												<span class='prd_info'>Price : ₹ ".$list['price']."</span>
												<span class='prd_info'>Category : ".$category."</span>
												<span class='prd_info'>Sold By : ".$info['company']."</span>
												<span class='prd_info'>Quantity : ".$list['qty']."</span>
											</div>
											<div class='col-md-2  float-end' >
												<span class='prd_price float-end' >₹ ".number_format($total,2)."</span>
												</div>
										</div>
										<div class='order_status_card'>
											<div class='card-body'>
												<div class='row'>
													<div class='col-lg-6 col-sm-12'>
														<h5 class='card-title'>".$order_status_text."</h5>
														<h5 class='date'>".$sub_ord_sts_txt." ".$info['company']." on ".date('d  F  Y h:i a',strtotime($status_time_date))."</h5>
													</div>
													<div class='col-lg-6 col-sm-12'>
														".$return_button."
													</div>
												</div>
											</div>
											<div class='row'>
												".$item_content."
												<div class='col-lg-3 col-md-12 col-sm-12 invoice_col dwnd_invoice_btn' >
												".$invoice_details."
												</div>
											</div>
										    <div>".$replace_order_info."</div>
										</div>
										".$reviewe_laouyt."
									</div>
								";
						if($i<$prd_count) {			
							$layout .="<hr>";
						}
						$i++;
			}
		}
		return $layout;
	}

	function manageOrderItems($order_id="")
	{
		$layout = "";
		$query = "SELECT I.id,I.product_id,I.order_id,I.variant_id,I.price as prize,I.total_tax,I.final_price,I.qty,I.size,I.total_amount,I.sgst,I.cgst,I.igst,I.sgst_amt,I.cgst_amt,I.created_at,O.order_uid,P.product_name,V.variant_name,VI.company,(SELECT file_name FROM ".MEDIA_TBL." WHERE item_id=P.id AND item_type='product' AND delete_status=0 ORDER BY P.id ASC LIMIT 1) as product_image,(SELECT file_name FROM ".MEDIA_TBL." WHERE item_id=P.id AND item_type='product' AND delete_status=0 AND is_default=1 ORDER BY P.id ASC LIMIT 1) as default_product_image FROM  ".ORDER_ITEM_TBL." I LEFT JOIN ".PRODUCT_TBL." P ON(P.id=I.product_id) LEFT JOIN ".ORDER_TBL." O ON (O.id=I.order_id) LEFT JOIN ".VENDOR_TBL." VI ON (VI.id=I.vendor_id) LEFT JOIN ".PRODUCT_VARIANTS." V ON (I.variant_id=V.id) WHERE I.order_id='".$order_id."' ORDER BY I.price*I.qty   DESC ";
		$exe = $this->exeQuery($query);
		if(mysqli_num_rows($exe)>0){
			$i = 1;
			while ($details = mysqli_fetch_array($exe)) {
				   $list  = $this->editPagePublish($details);

			   // Product Image
			   if ($list['default_product_image']!="") {
					$product_image = $list['default_product_image']!='' ? SRCIMG.$list['default_product_image'] : ASSETS_PATH."no_img.jpg" ;# code...
				}else{
					$product_image = $list['product_image']!='' ? SRCIMG.$list['product_image'] : ASSETS_PATH."no_img.jpg" ;
				}
				$product_name = $this->publishContent($list['product_name']);
				$company_name = ($list['company']!="") ? $list['company'] : 'Sapiens';

				$layout .="
						<div class='row status'>
							<div class='col-md-1'>
									<img src='".$product_image."' width=100% class='my_ord_item_img' />
							</div>
							
							<div class='col-md-3'>
								<div class='my_account_forms'>
									<span class='name'>".$product_name."</span>
									<span class='info'>".$list['variant_name']."</span>
									<span class='name'>Sold By: ".$company_name."</span>
								</div>
							</div>
							<div class='col-md-6 col-sm-12 date_in_prog'>
								<ol class='progtrckr' data-progtrckr-steps='4'>
									<li class='progtrckr-done'>Ordered</li><!--
								 --><li class='progtrckr-done'>Packed</li><!--
								 --><li class='progtrckr-done'>Shipped</li><!--
								 --><li class='progtrckr-todo'>Delivered</li>
								</ol>
								<div><span>".date('D, dS M',strtotime($list['created_at']))."</span></div>
							</div>
							<div class='col-md-2 cancel_hover my_ord_action_btn'>
								<div class='order_can_buttuon '>
									<td data-title='Action'><a class='btn btn-danger btn-sm'>Cancel</a></td>
								</div>
							</div>
						</div>";
			}
		}
		return $layout;
	}

	function rateVendorInfo($data)
	{	
		$result       = array();
		$vendor_id    = $this->decryptData($data["vendor_id"]);
		$product_id   = $this->decryptData($data["product_id"]);
		$vendor_info  = $this->getDetails(VENDOR_TBL,"*","id='".$vendor_id."'");
		$product_info = $this->getDetails(PRODUCT_TBL,"*","id='".$product_id."'");

		$result['vendor_info']  = $vendor_info;
		$result['product_info'] = $product_info;

		return json_encode($result);
	} 

	function addVendorRating($input)
	{	
		$user_id 	= @$_SESSION['user_session_id'];
		$vendor_id  = $this->decryptData($input["vendor_id"]);
		$product_id = $this->decryptData($input["product_id"]);
		$order_id   = $this->decryptData($input["order_id"]);
		$data 		= $this->cleanStringData($input);
		$check 		= $this ->check_query(VENDOR_RATTING_TBL,"id"," vendor_id='".$vendor_id."' AND order_id='".$order_id."' AND product_id ='".$product_id."' AND added_by = '".$user_id."'  ");
			if ($user_id) {
				$cus_info = $this->getDetails(CUSTOMER_TBL,"id,name,email","id='".$user_id."'");
				if ($check==0) {
					$curr 			= date("Y-m-d H:i:s");
					$query = "INSERT INTO ".VENDOR_RATTING_TBL." SET 
								vendor_id 		= '".$vendor_id."',
								product_id 		= '".$product_id."',
								order_id 		= '".$order_id."',
								name 			= '".$cus_info['name']."',
								email 			= '".$cus_info['email']."',
								star_ratings 	= '".$data['star_ratings']."',
								status			= '1',
								added_by 		= '".$user_id."',	
								created_at 		= '".$curr."',
								updated_at 		= '".$curr."' ";
					$exe   = $this->exeQuery($query);
					if($exe){
						 return 1;
					}else{
						 return "0"."`Sorry!! Unexpected Error Occurred. Please try again.!";
					}	
				}else{
					return"0"."`You have already rated for this product with same vendor";
				}
			} else {
				return"0"."`Please login for review product";
			}
	}

	// Vendor Rating edit form

	function getEditVendorRateForm($rating_id='')
	{	
		$layout       = "";
		$check_review = $this->check_query(VENDOR_RATTING_TBL,"id","id='".$this->decryptData($rating_id)."' ");

		if($check_review) {
			$vendor_rating_info = $this->getDetails(VENDOR_RATTING_TBL,"*","id='".$this->decryptData($rating_id)."'");
			$vendor_info        = $this->getDetails(VENDOR_TBL,"*"," id='".$vendor_rating_info['vendor_id']."' ");
			$product_info       = $this->getDetails(PRODUCT_TBL,"*"," id='".$vendor_rating_info['product_id']."' ");
			
			$layout = "
                    <input type='hidden' name='vendor_rating_id' id='edit_rating_id'  value='".$vendor_rating_info['id']."'>
                    <div class='modal-body'>
                        <div class='row'>
                          <h4 class='form-label modal-title'> Vendor Details</h4>
                            <div class='col-md-12'>
                                <div class='row'>
                                	<div class='col-md-3'>
                                		<span class='span_tag'>Vendor</span>
                                	</div>
                                	<div class='col-md-9'>: 
                                		<span class='info_tag' id='vendor_company'>".$vendor_info['company']."</span>
                                	</div>
                                </div>
                                <div class='row'>
                                	<div class='col-md-3'>
                                		<span class='span_tag'>Location</span>
                                	</div>
                                	<div class='col-md-9'>: 
                                		<span class='info_tag' id='venodr_location'>".$vendor_info['city']."</span>
                                	</div>
                                </div>
                                <div class='row'>
                                	<div class='col-md-3'>
                                		<span class='span_tag'>Product</span>
                                	</div>
                                	<div class='col-md-9'>:
                                		<span class='info_tag' id='customer_product'>".$product_info['product_name']."</span>
                                	</div>
                                </div>
                            </div>
                        </div>
                        <div class='row'>
                            <div class='col-lg-12'>
                                <div class='product_rating mt-10'>
                                	<h4>Your rating</h4>
                                    <span class='star-rating_vendor_edit'></span>
                                    <input type='hidden' name='star_ratings' class='edit_ven_rat_data' value='".$vendor_rating_info['star_ratings']."' id='edit_vendor_rating_input'>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class='modal-footer'>
                        <button type='button' class='btn btn-secondary rounded-pill' data-bs-dismiss='modal' onclick='orderProductReturn.reset();'>Cancel</button>
                        <button type='submit' class='btn btn-hero rounded-pill'>Update</button>
                    </div>";
			
		} else {
			$vendor_rating_info = array();
			return "Sorry!! Unexpected Error Occurred. Please try again.";
		}


		$result 				= array();
		$result['layout'] 		= $layout;
		$result['rating_info']  = $vendor_rating_info;

		return json_encode($result);
	}

	function editVendorRating($input='')
	{
		$user_id 	= @$_SESSION['user_session_id'];
		$rating_id  = $input["vendor_rating_id"];
		$data 		= $this->cleanStringData($input);
		$check 		= $this ->check_query(VENDOR_RATTING_TBL,"id"," id='".$rating_id."' AND vendor_id='".$vendor_id."' AND product_id ='".$product_id."' AND added_by = '".$user_id."'  ");
			if ($user_id) {
				$cus_info = $this->getDetails(CUSTOMER_TBL,"id,name,email","id='".$user_id."'");
				if ($check==0) {
					$curr 			= date("Y-m-d H:i:s");
					$query = "UPDATE ".VENDOR_RATTING_TBL." SET 
								star_ratings 	= '".$input['star_ratings']."',
								updated_at 		= '".$curr."'
								WHERE id='".$rating_id."' ";
					$exe   = $this->exeQuery($query);
					if($exe){
						 return 1;
					}else{
						 return "0"."`Sorry!! Unexpected Error Occurred. Please try again.!";
					}	
				}else{
					return"0"."`You have already rated for this product with same vendor";
				}
			} else {
				return"0"."`Please login for review product";
			}
	}

	//Product Reviews

	function getCustomerReviewAndRatings($product_id,$vendor_id,$order_id)
	{
		$result    = array();
		$condition = array();
		$layout    = "<div class='reviews_wrapper'>";

		// Basic review condition check

		$ids 		= array();
		$q   		= "SELECT id FROM ".ORDER_TBL." WHERE user_id='".@$_SESSION['user_session_id']."' ";
		$exe 		= $this->exeQuery($q);
		while($list = mysqli_fetch_array($exe)){
			$ids[]  =	$list['id'];
		}
		if(count($ids) > 0){
			$in_order = $this->getDetails(ORDER_ITEM_TBL,"order_id"," order_id IN (" . implode(',', array_map('intval',$ids)). ") AND product_id='".$product_id."'  AND order_status='2' "  );
		} else {
			$in_order = 0;
		}

		$condition['in_order'] = $in_order;

		// Check if this user already reviewed this product 

		$reviewed              = $this->check_query(REVIEW_TBL,"id"," product_id='".$product_id."' AND order_id='".$order_id."'  AND added_by='".@$_SESSION['user_session_id']."'");

		$condition['reviewed'] = $reviewed;


		$q = "SELECT R.id,R.product_id,R.name,R.email,R.admin_replay,R.comment,R.star_ratings,R.replay_at,R.approval_status,R.del_status,R.status,R.added_by,R.created_at,R.updated_at,C.id as customer_id,C.name as cus_name  FROM ".REVIEW_TBL." R LEFT JOIN ".CUSTOMER_TBL." C ON(R.added_by=C.id) WHERE R.product_id='".$product_id."' AND R.order_id='".$order_id."' AND R.added_by='".$_SESSION['user_session_id']."'  AND R.del_status='0' AND R.status='1' ORDER BY R.id DESC " ;

	 	$exe = $this->exeQuery($q);
			 	
	 	if(mysqli_num_rows($exe)>0) {
	 		    	while($row = mysqli_fetch_array($exe)){
	    				$list  = $this->editPagePublish($row);

	    				$admin_replay = "<div class='comment_text admin_replay_box_myorder'>
											<div class='reviews_meta '>
												<p><strong>Sapiens </strong>- ". date("F j, Y", strtotime($list['replay_at']))."</p>
												<span>".$list['admin_replay'] ."</span>
											</div>
										</div>";

						if($list['admin_replay']=="" || $list['admin_replay']==NULL) {
							$admin_replay = "";
						}

						if($_SESSION['user_session_id'] == $list['customer_id']) {
							$edit_btn = "<a type='button' data-review_id='".$this->encryptData($list['id'])."' class='btn btn-hero btn-sm float-end editProductReview'>Edit</a>";
						} else {
							$edit_btn = "";
						}

						if($list['approval_status']==1) {
							$layout .= "
								<h4 class='form-label '> Product Review & Rating</h4>
 		    					<div class='reviews_comment_box'>
                                    <div class='comment_thmb'>
                                        <img src='".BASEPATH."lib/images/img/blog/comment2.jpg' alt=''>
                                    </div>
                                    <div class='comment_text'>
                                        <div class='reviews_meta'>
									       <div class='star_rating'>
									           <span class='my-rating-7'></span>
									           <input type='hidden' class='rating_data' name='star_ratings' value='".$list['star_ratings']."' id='rating_data'>
									        </div>
                                            <p>
                                            	<strong>".$list['cus_name'] ." </strong> - ". date("F j, Y", strtotime($list['created_at']))."
                                            </p>
                                            ".$edit_btn."
                                            <span>".$list['comment'] ."</span>
											$admin_replay
                                    	</div>
                                    </div>
                                </div>";
                        } else {
							$layout = "<h4 class='form-label'> Product Review & Rating</h4>
										<div class='reviwe_publish_msg ms-2'>
											Your review will publish soon
										</div> ";
                        }
	 		    		
			    	}
	 	} else {
	 		$layout .= "<h4 class='form-label '> Product Review & Rating</h4>
	 					<a type='button' data-product_id='".$this->encryptData($product_id)."' data-order_id='".$this->encryptData($order_id)."' class='btn btn-hero addProductReview rounded-pill ps-2 pe-2'>Rate & Review Product</a>";
	 	}

	 	// Check if this user already rated this vendor for the same product 

		$rated = $this->check_query(VENDOR_RATTING_TBL,"id"," product_id='".$product_id."' AND order_id='".$order_id."' AND vendor_id='".$vendor_id."'  AND added_by='".@$_SESSION['user_session_id']."'");

		$result['vendor_rating'] = $rated;

		if($rated > 0) {
			$vendor_rated_info = $this->getDetails(VENDOR_RATTING_TBL,"*"," product_id='".$product_id."'AND order_id='".$order_id."' AND vendor_id='".$vendor_id."'  AND added_by='".@$_SESSION['user_session_id']."'");

			$vendor_info       = $this->getDetails(VENDOR_TBL,"company","id='".$vendor_rated_info['vendor_id']."'");

			if($_SESSION['user_session_id'] == $vendor_rated_info['added_by']) {
				$edit_btn = "
							<a type='button' data-rating_id='".$this->encryptData($vendor_rated_info['id'])."' class='btn btn-hero btn-sm rounded-pill px-3 float-end editVendorRating'>Edit
							</a>";
			} else {

				$edit_btn = "";

			}

			$layout .= "
					<h4 class='form-label mt-10'> Vendor Rating</h4>
					<div class='reviews_comment_box'>
		                <div class='comment_thmb'>
		                    <img src='".BASEPATH."lib/images/img/blog/comment2.jpg' alt=''>
		                </div>
		                <div class='comment_text'>
		                ".$edit_btn."
		                <div class='star_rating float_left'>
	                        	<p>
	                        		<strong>".$vendor_info['company'] ." </strong> - ". date("F j, Y", strtotime($vendor_rated_info['created_at']))."
	                        	</p>
	                        	<span class='my-rating-7'></span>
	                        	<input type='hidden' class='rating_data' name='star_ratings' value='".$vendor_rated_info['star_ratings']."' id='rating_data'>
					        </div>
		                </div>
		            </div>";

		} else {
	 		$layout .= "<h4 class='form-label mt-10'> Vendor Rating</h4> 
	 					<a type='button' data-product_id='".$this->encryptData($product_id)."' data-vendor_id='".$this->encryptData($vendor_id)."' data-order_id='".$this->encryptData($order_id)."' class='btn btn-hero addVendorRating rounded-pill ps-3 pe-3'>Rate a Vendor</a>";
	 	}

		$layout .= "</div>";

	 	return $layout;
	}

	// Review info

	function getReviewInfo($data)
	{	
		$review_id      = $this->decryptData($data);
		$review_details = $this->getDetails(REVIEW_TBL,"*","id='".$review_id."'");
		return json_encode($review_details);
	}

	function manageInvoiceItems($order_id="",$vendor_id="")
    {    
        $layout = "";
        $query = "SELECT I.id,I.qty,I.product_id,I.order_id,I.vendor_id,I.variant_id,I.price as prize,I.total_tax,I.sub_total,I.final_price,I.qty,I.size,I.total_amount,I.sgst,I.cgst,I.igst,I.sgst_amt,I.cgst_amt,O.coupon_code,O.order_address,O.coupon_id,I.coupon_value,O.order_uid,P.product_name,V.variant_name,VD.state_id,VD.state_name,O.shipping_value,O.shipping_tax,O.shipping_tax_value,O.shipping_cost,
        (SELECT SUM(igst_amt) FROM ".ORDER_ITEM_TBL." WHERE order_id='".$order_id."' AND vendor_id='".$vendor_id."') as totalTax,
        (SELECT SUM(tax_amt) FROM ".ORDER_ITEM_TBL." WHERE order_id='".$order_id."' AND vendor_id='".$vendor_id."') as subTotal,
        (SELECT SUM(coupon_value) FROM ".ORDER_ITEM_TBL." WHERE order_id='".$order_id."' AND vendor_id='".$vendor_id."') as couponTotal,
        (SELECT SUM(total_amount) FROM ".ORDER_ITEM_TBL." WHERE order_id='".$order_id."' AND vendor_id='".$vendor_id."') as totalAmount

        FROM  ".ORDER_ITEM_TBL." I LEFT JOIN ".PRODUCT_TBL." P ON(P.id=I.product_id) LEFT JOIN ".ORDER_TBL." O ON (O.id=I.order_id) LEFT JOIN ".PRODUCT_VARIANTS." V ON (I.variant_id=V.id) LEFT JOIN ".VENDOR_TBL." VD ON (VD.id=I.vendor_id) WHERE I.order_id='".$order_id."' AND I.vendor_id='".$vendor_id."' ORDER BY I.price*I.qty LIMIT 1 ";
		$exe = $this->exeQuery($query);
	 	if(mysqli_num_rows($exe)>0){
            $i = 1;
            while ($details = mysqli_fetch_array($exe)) {
		    	$list  = $this->editPagePublish($details);

                $total_tax = $list['totalTax'] * $list['qty'] ;
				$name      = $list['variant_id']==0 ? $list['product_name'] : $list['product_name']." - ".$list['variant_name'] ;
				$coupon    = $list['coupon_id']!=0 ? $list['coupon_code'] : "-";
				$coupon_value    = $list['coupon_id']!=0 ? $list['coupon_value'] : "0.00";

                $layout .='
					 '.$this->getInvoiceItems($order_id,$vendor_id).'
					 ';
				if($list['coupon_id']=="coupon_id"){
					$layout .='
						<tr>
							<td style="border-bottom: 1px solid #222;border-top: 1px solid #222;text-align: right;">Coupon Code</td>
							<td style="border-bottom: 1px solid #222;border-top: 1px solid #222;text-align: right;">'.$coupon.'</td>
							<td colspan="2"style="border-bottom: 1px solid #222;border-top: 1px solid #222;text-align: right;">Coupon Value</td>
							<td style="border-bottom: 1px solid #222;border-top: 1px solid #222;text-align: right">'.$coupon_value.'</td>
						</tr>
						';
				}else{
					$layout .='
						
						';
				}
				
				$layout .='
			        	<tr style="background: #F5F5F5;">
							<td colspan="5" style="text-align: right;border-top: 1px solid #222;;"><strong>Subtotal</strong></td>
							<td colspan="2" style="text-align: right;border-top: 1px solid #222;">Rs. '.number_format($list["subTotal"] + $list['totalTax'],2).'</td>
			          	</tr>
			          	<tr style="background: #F5F5F5;">
							<td colspan="5" style="text-align: right;"><strong>Discount</strong></td>
							<td colspan="2" style="text-align: right;">Rs. '.$this->inrFormat((($list['couponTotal']!='') ? $list['couponTotal'] : 0.00)).'</td>
			         	</tr>
			         	<tr style="background: #F5F5F5;">
						    <td colspan="5" style="text-align: right;"><strong>Shipment Charges</strong> (shipping charge : Rs.'.number_format($list["shipping_value"],2).' + SGST : '.($list["shipping_tax"]/2).'% (Rs.'.number_format($list['shipping_tax_value']/2,2).') + CGST : '.($list["shipping_tax"]/2).'% (Rs.'.number_format($list['shipping_tax_value']/2,2).'))</td>

						    <td colspan="2" style="text-align: right;">Rs. '.number_format($list["shipping_cost"],2).'</td>
						</tr>
						';
				if($list['couponTotal']!=''){
					$layout .='
						<tr style="background: #F5F5F5;">
							<td colspan="3" style="border-bottom: 1px solid #222;border-top: 1px solid #5D6975;">Total Amount in Words: <strong>'.$this->amountInWords($list["subTotal"] + $list['shipping_cost'] + $list['totalTax']).'</strong></td>
				  			<td colspan="2" style="border-bottom: 1px solid #222;text-align: right; border-top: 1px solid #5D6975;"><strong>GRAND TOTAL</strong></td><td colspan="3" style="border-bottom: 1px solid #222;text-align: right; border-top: 1px solid #5D6975;;"><strong>Rs. '.number_format($list["subTotal"] + $list['shipping_cost'] +$list['totalTax']-$list['couponTotal'],2).'</strong></td>
						</tr>
						';
				}else{
					$layout .='
						<tr style="background: #F5F5F5;">
							<td colspan="3" style="border-bottom: 1px solid #222;border-top: 1px solid #5D6975;">Total Amount in Words: <strong>'.$this->amountInWords($list["subTotal"] + $list['shipping_cost'] + $list['totalTax']).'</strong></td>
				  			<td colspan="2" style="border-bottom: 1px solid #222;text-align: right; border-top: 1px solid #5D6975;"><strong>GRAND TOTAL</strong></td>
							<td colspan="2" style="border-bottom: 1px solid #222;text-align: right; border-top: 1px solid #5D6975;;"><strong>Rs. '.number_format($list["subTotal"] + $list['shipping_cost'] + $list['totalTax'],2).'</strong></td>
			          	</tr>
			          ';
				}
            $i++;
            }
        }
        return $layout;
    }

    function getInvoiceItems($order_id="",$vendor_id="")
    {    
        $layout = "";
        $query = "SELECT I.id,I.qty,I.product_id,I.order_id,I.variant_id,I.price as prize,I.total_tax,I.sub_total,I.final_price,I.qty,I.size,I.total_amount,I.sgst,I.cgst,I.igst,I.sgst_amt,I.cgst_amt,I.igst_amt,I.tax_type,O.order_address,O.order_uid,P.product_name,V.variant_name,O.order_address,I.vendor_id,VD.state_id,VD.state_name FROM  ".ORDER_ITEM_TBL." I LEFT JOIN ".PRODUCT_TBL." P ON(P.id=I.product_id) LEFT JOIN ".VENDOR_TBL." VD ON (VD.id=I.vendor_id) LEFT JOIN ".ORDER_TBL." O ON (O.id=I.order_id) LEFT JOIN ".PRODUCT_VARIANTS." V ON (I.variant_id=V.id)  WHERE I.order_id='".$order_id."' AND I.vendor_id='".$vendor_id."' ORDER BY I.price*I.qty   DESC ";
		$exe = $this->exeQuery($query);
	 	if(mysqli_num_rows($exe)>0){
            $i = 1;
            while ($details = mysqli_fetch_array($exe)) {
		    	$list  = $this->editPagePublish($details);
		    	$background = ($i %2 == 0)? "background: #F5F5F5;": "";

                $total_tax = $list['total_tax'] * $list['qty'] ;
				$name      = $list['variant_id']==0 ? $list['product_name'] : $list['product_name']." - ".$list['variant_name'] ;

				$billing_address = $this->getDetails(CUSTOMER_ADDRESS_TBL,"state_id","id='".$list['order_address']."' ");

				if($billing_address['state_id']==$list['state_id']) {
					$tax_info = $list['sgst']."% SGST<br/>".$list['cgst']."% CGST";
				} else {
					$tax_info = $list['igst']."% IGST";
				}

                $layout .='
                		<tr>
						<td style="border-bottom: 1px solid #fff;border-top: 1px solid #222;text-align: center;">'.$i.'</td>
						<td style="border-bottom: 1px solid #fff;border-top: 1px solid #222;text-align: left;">'.$this->publishContent($name).'</td>
						<td style="border-bottom: 1px solid #fff;border-top: 1px solid #222;text-align: center;">Rs.'.number_format($list['prize'],2).'</td>
						<td style="border-bottom: 1px solid #fff;border-top: 1px solid #222;text-align: center;">'.$this->publishContent($list['qty']).'</td>
						<td style="border-bottom: 1px solid #fff;border-top: 1px solid #222;text-align: center;">'.$tax_info.'</td>
						<td style="border-bottom: 1px solid #fff;border-top: 1px solid #222;text-align: center;">Rs.'.number_format($list['igst_amt'],2).'</td>
						<td style="border-bottom: 1px solid #fff;border-top: 1px solid #222;text-align: right;">Rs.'.number_format($list['sub_total'],2).'</td>
						</tr>
					 
			          ';
						// <td style="border-bottom: 1px solid #fff;border-top: 1px solid #222;text-align: center;">Rs.'.round($list['igst_amt']).'</td>

            $i++;
            }
        }
        return $layout;
    }

    
		

/*-----------Dont'delete---------*/
}?>