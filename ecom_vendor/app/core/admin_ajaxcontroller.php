<?php	
ini_set('max_execution_time', 500);	
require_once './../../global/global-config.php';
require_once '../config/config.php';
require_once '../app/models/Model.php';
require_once 'classes/PHPMailerAutoload.php';

class Ajaxcontroller extends Model
{		

	/*--------------------------------------------- 
			Vendor Login Authentication
	----------------------------------------------*/ 

	// User login

	function userLogin($data){
		$email 		= $data['email'];
		$passw 		= $data['password'];
		$password 	= $this->encryptPassword($passw);
		$check = $this -> check_query(VENDOR_TBL,"id"," email='$email' AND password ='$password' AND status='1' ");
		if($check == 1){
        	$userinfo = $this -> getDetails(VENDOR_TBL,"id"," email='$email' AND password ='$password' AND status='1' ");	
         	$_SESSION["ecom_vendor_id"] 	= $userinfo["id"];
         	$this->setPermissionSession($userinfo['id']);
         	// return false;
         	//print_r($_SESSION);
         	
         	//return $this->sessionIn($user_type="admin",$admin_type=$_SESSION["admin_type"],$_SESSION["ecom_vendor_id"],"web","browser");
         	if($this->sessionIn($_SESSION["ecom_vendor_id"],"web","browser")){
         		return 1;
         	}          	
        }else{
        	return  "Sorry your account details are wrong.";
        }
	}


	// Reset Password

	function resetPassword($data)
	{	
		if(isset($_SESSION['user_reset_password'])){
			if($this->cleanString($data['fkey']) == $_SESSION['user_reset_password']){
				$curr  		  = date("Y-m-d H:i:s");
				$vendor_token = $_SESSION['user_reset_password'];
				$query        =" UPDATE ".VENDOR_TBL." SET
					                password        = '". $this->encryptPassword($data['password'])."',
					                password_normal = '".$data['password']."',
					                updated_at      ='".$curr."'
					             WHERE token='".$vendor_token."' "; 
	            $exe=$this->exeQuery($query);
				if($exe){
					$this->exeQuery("UPDATE ".VENDOR_FORGOT_PASSWORD." SET update_status = '1' WHERE vendor_token = '".$vendor_token."' ");
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

	// Set Permission Sessions

	function setPermissionSession($user_id)
	{
		$q = "SELECT * FROM ".PERMISSION_TBL." WHERE user_id='$user_id' ";
	    $query = $this->exeQuery($q);
	    $list = mysqli_fetch_assoc($query);
		if($list) {
	    	foreach ($list as $key => $value) {
				$_SESSION[$key] = $value;
			}
	    }
	}



//---- End ----//	
		
}


?>