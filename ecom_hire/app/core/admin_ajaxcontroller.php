<?php	
ini_set('max_execution_time', 500);	
require_once './../../global/global-config.php';
require_once '../config/config.php';
require_once '../app/models/Model.php';
require_once 'classes/PHPMailerAutoload.php';

class Ajaxcontroller extends Model
{		

	/*--------------------------------------------- 
			Contractor Login Authentication
	----------------------------------------------*/ 

	// User login

	function userLogin($data){
		$email 		= $data['email'];
		$passw 		= $data['password'];
		$password 	= $this->encryptPassword($passw);
		$check = $this -> check_query(CONTRACTOR_TBL,"id"," email='$email' AND hash_password ='$password' AND status='1' ");
		if($check == 1){
        	$userinfo = $this -> getDetails(CONTRACTOR_TBL,"id"," email='$email' AND hash_password ='$password' AND status='1' ");	
         	$_SESSION["ecom_contractor_id"] 	= $userinfo["id"];
         	$this->setPermissionSession($userinfo['id']);
         	// return false;
         	//print_r($_SESSION);
         	
         	//return $this->sessionIn($user_type="admin",$admin_type=$_SESSION["admin_type"],$_SESSION["ecom_contractor_id"],"web","browser");
         	if($this->sessionIn($_SESSION["ecom_contractor_id"],"web","browser")){
         		return 1;
         	}          	
        }else{
        	return  "Sorry your account details are wrong.";
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