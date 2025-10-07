<?php

require_once 'Model.php';
require_once 'app/core/classes/PHPMailerAutoload.php';

class User extends Model
{

	// User Login

	function userLogin($data)
	{
		$email 		= $data['email'];
		$passw 		= $data['password'];
    	$password 	= $this->encryptPassword($passw);
		$check 		= $this->check_query(CUSTOMER_TBL,"id"," (email='".$email."' OR mobile = '".$email."') AND password='".$password."' AND status='1' ");
		if($check == 1){
			$check_verify = $this->check_query(CUSTOMER_TBL,"id"," (email='".$email."' OR mobile = '".$email."') AND password='".$password."' AND status='1' AND (mobile_verify='1' OR email_verify = '1') ");
			if($check_verify == 1){
	        	$userinfo 					 = $this -> getDetails(CUSTOMER_TBL,"id"," (email='".$email."' OR mobile = '".$email."') AND password='".$password."' ");	
	         	$_SESSION["user_session_id"] = $userinfo["id"];
	         	$_SESSION["login_success"]   = "success";

	         	$query = "SELECT * FROM ".CART_TBL." WHERE user_id='".$userinfo['id']."' ";
				$exe   = $this->exeQuery($query);
				if(mysqli_num_rows($exe) > 0) 
				{
					while ($list = mysqli_fetch_assoc($exe)) {
						$check_cart_order_status = $this->check_query(ORDER_ITEM_TBL,"*","cart_id='".$list['id']."'");
						if($check_cart_order_status==0)
						{
							$_SESSION["user_cart_id"] = $list['id'];
						}				
					}
				}
	         	if($this->sessionIn($_SESSION["user_session_id"],"web","browser")){
	         		$cart_id = @$_SESSION['user_cart_id'];
	         		$update_userid = $this->updateUserCartid($_SESSION["user_session_id"],$cart_id);
	         		return 1;
         		} 
         	}else{
        		return "1`".$this->errorMsg("Your account wasn't verified.");
      		}        	
        }else{
        	return "2`".$this->errorMsg("Email or Password is not valid.");
        }
	}

	// Update Card Id in Cart table

	function updateUserCartid($user_id,$cart_id)
    {        
        $curr  = date("Y-m-d H:i:s");
        $check = $this->check_query(CUSTOMER_ADDRESS_TBL,"id","user_id= '".$user_id."' AND delete_status='0' ");
		if ($check =="1") {
			$addressinfo = $this->getDetails(CUSTOMER_ADDRESS_TBL,"id","user_id='".$user_id."' LIMIT 1");
			$address  	 = $addressinfo['id'];
		}else{
			$address = "0";
		}
        $q="UPDATE  ".CART_TBL." SET 
            user_id      = '".$user_id."',
            shipping_id  = '".$address."',
            updated_at   = '".$curr."' WHERE id='".$cart_id."' ";
        $exe     = $this->exeQuery($q);
        if ($exe) {
        	$query="UPDATE  ".CART_ITEM_TBL." SET 
		            user_id      = '".$user_id."',
		            updated_at   = '".$curr."' WHERE cart_id='".$cart_id."' ";
	        $result=$this->exeQuery($query);
	        return 1;
        }else{
            return  "Unexpected Error Occurred";
        }
    }


	// User Register
	
	function userRegister($data="")
	{
		
		$check = $this -> check_query(CUSTOMER_TBL,"id","email='".$data['email']."' ");
		if ($check == 0) {
			$check_mbl = $this -> check_query(CUSTOMER_TBL,"id","mobile ='".$data['mobile']."' ");
			if ($check_mbl == 0) {
				$token 			= $this->generateRandomString("30").date("Ymdhis");
				$curr 			= date("Y-m-d H:i:s");
				$today 			= date("Y-m-d");
				$password 		= $this->encryptPassword($data['password']);
				$mobile_token 	= $this->mobileToken(4);

				if(isset($data['subscribe_check'])) {
					$query = "INSERT INTO ".SUBSCRIBE_TBL." SET 
				        email 			= '".$this->cleanString($data['email'])."',
				       	sub_date		= '".$curr."',
						status			= '1',
				        delete_status 	= '0',
				        created_at 		= '".$curr."',
				        updated_at 		= '".$curr."' ";
				    $exe = $this->exeQuery($query);
				}

				$query="INSERT INTO ".CUSTOMER_TBL." SET
						token 				= '".$token."',
						name 				= '".$this->cleanString($data['name'])."',
						email 				= '".$this->cleanString($data['email'])."',
						mobile 				= '".$this->cleanString($data['mobile'])."',
						password 			= '".$password."',
						mobile_token 		= '".$mobile_token."',
						registration_date 	= '".$today."',
						status				= '1',
						created_at 			= '".$curr."',
						updated_at 			= '".$curr."' ";
				$exe = $this->exeQuery($query);
				if($exe){
					$sender 		= COMPANY_NAME;
					$sender_mail 	= NO_REPLY;
					$subject 		= COMPANY_NAME." Registration - Account verification OTP";
					$receiver_mail 	= $this->cleanString($data['email']);
					$user_token 	= $this->encryptData($token);
					$message  		= $this->emailOtpTemp($data['name'],$mobile_token);
					$send_mail 		= $this->send_mail($sender_mail,$receiver_mail,$subject,$message);
					return "1`".$token;
				}
			}else{
				return "Entered Mobile Number is already registered. Please Change the Mobile Number or Try Login.";
			}	
		}else{
				return "Entered email is already registered. Please Change the email or Try Login.";
		}				
	}

	// Classified Register
	
	function classifiedRegister($data="")
	{	
		$name = $this->hyphenize($data['name']);
		$check_token = $this->check_query(CONTRACTOR_TBL,"id"," token='".$name."' ");
		if ($check_token==0) {
			$token = $name;
		}else{
			$token = $name."-".$this->generateRandomString("5");
		}
		
		$curr 	  = date("Y-m-d H:i:s");
		$password = $this->encryptPassword($data['password']);

		$check_email = $this -> check_query(CONTRACTOR_TBL,"id","email='".$data['email']."' ");
		if($check_email==0) {
			$check_mobile = $this -> check_query(CONTRACTOR_TBL,"id","mobile='".$data['mobile']."' ");
			if($check_mobile==0) {
				$query = "INSERT INTO ".CONTRACTOR_TBL." SET 
						token 			= '".$token."',
						name			= '".$this->cleanString($data['name'])."',
						mobile 			= '".$this->cleanString($data['mobile'])."',
						email 			= '".$this->cleanString($data['email'])."',
						hash_password 	= '".$password."',
						password 		= '".$data['password']."',
						invite_status   = '1',
						status			= '1',
						created_at 		= '$curr',
						updated_at 		= '$curr' ";
				$exe 	= $this->lastInserID($query);
				if($exe){
					$sender_mail 	= NO_REPLY;
					$sender 		= COMPANY_NAME;
			        $receiver_mail 	= $data['email'];
			        $subject        = "Expert Login details for"." - ".ucwords($data['name']);
			        $message 		= $this->contractorLoginInfo($data['name'],$data['email'],$data['password']);
			        $send_mail 		= $this->send_mail($sender_mail,$receiver_mail,$subject,$message);
					return 1;
				}else{
					return "Sorry!! Unexpected Error Occurred. Please try again.";
				}
				
			} else {
				return "Entered mobile number is already registered in another classified profile.";
			}
		} else {
			return "Entered email address is already registered in another classified profile.";
		}
						
	}

	// Verify User Account
	
	function verifyUserAccount($email)
	{		
			$user_detail    = $this->getDetails(CUSTOMER_TBL,"*","email ='".$email."' ");
			$token 			= $this->generateRandomString("30").date("Ymdhis");
			$mobile_token 	= $this->mobileToken(4);

			$query="UPDATE ".CUSTOMER_TBL." SET
						token 				= '".$token."',
						mobile_token 		= '".$mobile_token."' 
						WHERE id='".$user_detail['id']."'
						 ";
				$exe = $this->exeQuery($query);
				if($exe){
					$sender 		= COMPANY_NAME;
					$sender_mail 	= NO_REPLY;
					$subject 		= COMPANY_NAME." User - Account verification OTP";
					$receiver_mail 	= $this->cleanString($user_detail['email']);
					$user_token 	= $this->encryptData($token);
					$message  		= $this->emailOtpTemp($user_detail['name'],$mobile_token,"account_verify");
					$send_mail 		= $this->send_mail($sender_mail,$receiver_mail,$subject,$message);
					return "1`".$token;
				}
				
	}

	// register

	function findTempName($data)
	{
		$info = $this->getDetails(CUSTOMER_TBL,"name", "token='".$data."' ");
		return $info['name'];
	}

	function forgotPassword($data)
	{
		$curr  		= date("Y-m-d H:i:s");
		$today 		= date("Y-m-d");
		$token 	    = $this->generateRandomString("30").date("Ymdhis").$this->generateRandomString("16");
		$check      = $this->check_query(CUSTOMER_TBL,"email","email ='".$data['email']."' ");
		if ($check==1) {
			$info  = $this->getDetails(CUSTOMER_TBL,"token","email = '".$data['email']."' ");
			$query="INSERT INTO ".FORGOT_PASSWORD." SET 
					user_token 			= '".$info['token']."',
					token 				= '".$token."',
					status				= '1',
					created_at 			= '".$curr."',
					updated_at 			= '".$curr."' ";
			$exe=$this->exeQuery($query);
			if($exe){
				$email_info 	= $this->getDetails(CUSTOMER_TBL,'id,name,email,token', " email ='".$data['email']."' ");
				$sender 		= COMPANY_NAME;
				$sender_mail 	= NO_REPLY;
				$subject 		= COMPANY_NAME." - Reset Password Mail";
				$receiver_mail	= $email_info['email'];
				$user_token 	= $this->encryptData($email_info['token']);
				$message  	    = $this->forgotPasswordTemp($email_info['name'],$user_token,$token);
				$send_mail 		= $this->send_mail($sender_mail,$receiver_mail,$subject,$message);
				return 1;
			}else{
				return "Sorry!! Unexpected Error Occurred. Please try again.";
			}
		} else {
			return "Email id you have entered does not match with our records";
		}
	}


	// Reset Password

	function resetPassword($data)
	{	
		if(isset($_SESSION['user_reset_password'])){
			if($this->cleanString($data['fkey']) == $_SESSION['user_reset_password']){
				$curr  		= date("Y-m-d H:i:s");
				$user_token = $_SESSION['user_reset_password'];
				$query="UPDATE ".CUSTOMER_TBL." SET
		                password= '". $this->encryptPassword($data['new_password'])."',
		                updated_at='".$curr."' WHERE token='".$user_token."' "; 
	            $exe=$this->exeQuery($query);
				if($exe){
					$this->exeQuery("UPDATE ".FORGOT_PASSWORD." SET update_status = '1' WHERE user_token = '".$user_token."' ");
					unset($_SESSION['user_reset_password']);
					return 1;
				}else{
					return "Sorry!! Unexpected Error Occurred. Please try again.";
				}
			}else{
					return "Sorry!! Unexpected Error Occurred. Please try again.";
			}
		}else{
				return "Sorry!! Unexpected Error Occurred. Please try again.";
		}		
	}	

	function resendCode($token)
	{
		$check 			= $this->check_query(CUSTOMER_TBL,"id"," token='".$token."' AND email_verify ='0' ");
		if($check==1){
				$info 			= $this->getDetails(CUSTOMER_TBL,"mobile,email,mobile_token,name"," token='".$token."'  ");
				$curr 			= date("Y-m-d H:i:s");
				$mobile_token 	= $info['mobile_token'];
				$sender 		= COMPANY_NAME;
				$sender_mail 	= NO_REPLY;
				$subject 		= COMPANY_NAME." Registration - Account verification OTP";
				$receiver_mail 	= $this->cleanString($info['email']);
				$user_token 	= $mobile_token;
				$message 		= $this->emailOtpTemp($info['name'],$user_token,$mobile_token);
				$send_mail 		= $this->send_mail($sender_mail,$receiver_mail,$subject,$message);
				return "1`".$token;
		}else{
			return "0`<h4 class='text-danger text-center err_msg'> Your Email is already verified. Please Login to your account.</h4>";
		}
	}

	function validateEmail($data) 
	{	

		$curr        	= date("Y-m-d H:i:s");
		$token 			= ($data['token']);
		$mobile_token 	= ($data['verification_code']);
		$check 			= $this->check_query(CUSTOMER_TBL,"id"," token='".$token."' AND mobile_token ='".$mobile_token."' ");
		if($check==1){
			$query="UPDATE  ".CUSTOMER_TBL." SET 
		            email_verify      	= '1',
		            updated_at   		= '".$curr."' WHERE token='".$token."' ";
	        $exe=$this->exeQuery($query);
			return 1;
		}else{
			return "<h4 class='text-danger text-center err_msg'> Wrong Verification Code </h4>";
		}
	}


	function userVerificationOtp($data) 
	{	

		$curr        	= date("Y-m-d H:i:s");
		$token 			= ($data['token']);
		$mobile_token 	= ($data['verification_code']);
		$check 			= $this->check_query(CUSTOMER_TBL,"id"," token='".$token."' AND mobile_token ='".$mobile_token."' ");
		if($check==1){
			$query="UPDATE  ".CUSTOMER_TBL." SET 
		            email_verify      	= '1',
		            updated_at   		= '".$curr."' WHERE token='".$token."' ";
	        $exe=$this->exeQuery($query);
			return 1;
		}else{
			return "<h4 class='text-danger text-center err_msg'> Wrong Verification Code </h4>";
		}
	}





/*-----------Dont'delete---------*/
}?>