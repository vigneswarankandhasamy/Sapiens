<?php	
ini_set('max_execution_time', 500);	
require_once './../../global/global-config.php';
require_once '../config/config.php';
require_once '../app/models/Model.php';
require_once 'classes/PHPMailerAutoload.php';

class Ajaxcontroller extends Model
{		

	/*--------------------------------------------- 
				Migration Management
	----------------------------------------------*/ 

	// Add Migrations

	function addMigration($data)
	{
		if(isset($_SESSION['add_migrations'])){
			if($data['fkey'] == $_SESSION['add_migrations']){
				$curr 			= date("Y-m-d H:i:s");
				$added_by 		= $_SESSION["ecom_admin_id"];
			 	$query = "INSERT INTO ".MIGRATION_TBL." SET 
							name 			= '".$data['name']."',
							type 			= '".$data['type']."',
							sql_query 		= '".$data['sql_query']."',
							remarks 		= '".$data['remarks']."',
							added_by 		= '".$added_by."',
							status			= '1',
							created_at 		= '$curr',
							updated_at 		= '$curr'  ";
				$exe 	= $this->exeQuery($query);
				if($exe){
					// Save Database
					$db_update = $this->exeQuery($data['sql_query']);
					echo $db_update;
					//unset($_SESSION['add_migrations']);
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


	/*--------------------------------------------- 
			Auto Login Authentication
	----------------------------------------------*/ 

	// User login

	function userLogin($data){
		$email 		= $data['email'];
		$passw 		= $data['password'];
		$password 	= $this->encryptPassword($passw);
		$check = $this -> check_query(USERS_TBL,"id"," email='$email' AND password ='$password' AND status='1' ");
		if($check == 1){
        	$userinfo = $this -> getDetails(USERS_TBL,"id,is_super_admin,admin_type"," email='$email' AND password ='$password' AND status='1' ");	
         	$_SESSION["ecom_admin_id"] 	= $userinfo["id"];
         	$_SESSION["is_super_admin"] = $userinfo["is_super_admin"];
         	$_SESSION["admin_type"] = $userinfo["admin_type"];

         	$this->setPermissionSession($userinfo['id']);
         	// return false;
         	//print_r($_SESSION);
         	
         	//return $this->sessionIn($user_type="admin",$admin_type=$_SESSION["admin_type"],$_SESSION["ecom_admin_id"],"web","browser");
         	if($this->sessionIn($_SESSION["ecom_admin_id"],"web","browser")){
         		return 1;
         	}          	
        }else{
        	return  "Sorry your account details are wrong.";
        }
	}

	// Set Permission Sessions

	function setPermissionSession($user_id)
	{	
		$curr = date("Y-m-d H:i:s");
		$q = "SELECT * FROM ".PERMISSION_TBL." WHERE user_id='$user_id' ";
	    $exe = $this->exeQuery($q);
    	if(mysqli_num_rows($exe) > 0) {
    		$list = mysqli_fetch_assoc($exe);
    		foreach ($list as $key => $value) {
				$_SESSION[$key] = $value;
			}
    	} else {
    		$q = "INSERT INTO ".PERMISSION_TBL." SET
    				user_id    = '".$user_id."',
    				created_at = '".$curr."',
    				updated_at = '".$curr."' ";
    		$exe = $this->exeQuery($q);

    		if($exe) {
    			$q   = "SELECT * FROM ".PERMISSION_TBL." WHERE user_id='$user_id' ";
		    	$exe = $this->exeQuery($q);
		    	$list = mysqli_fetch_assoc($exe);
	    		foreach ($list as $key => $value) {
    				$_SESSION[$key] = $value;
    			}
    		}
    	}
	}

	// Edit Admin Profile

	function editAdminProfile($data)
	{
		$layout = "";
		if(isset($_SESSION['edit_profile_key'])){
			if($this->cleanString($data['fkey']) == $_SESSION['edit_profile_key']){
				$curr 			= date("Y-m-d H:i:s");
				echo $query = "UPDATE ".USERS_TBL." SET 
							ad_name = '".$this->cleanString($data['name'])."',
							ad_mobile = '".$this->cleanString($data['mobile'])."',
							ad_email = '".$this->cleanString($data['email'])."',
							updated_at = '$curr' WHERE ad_status=1 ";
				$exe 	= mysql_query($query);
				if($exe){
					unset($_SESSION['edit_profile_key']);
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

 	// Change Admin Password

	function changeAdminPassword($data)
	{
		if(isset($_SESSION['change_password_key'])){
			if($this->cleanString($data['fkey']) == $_SESSION['change_password_key']){
				$curr 			= date("Y-m-d H:i:s");
   		 		$check_password = $this->check_query(ADMIN_TBL,"id", " id= '".$_SESSION['ecom_admin_id']."' AND
			          ad_password= '".$this->encryptPassword($data['password'])."' ");
			    if($check_password==1){
			        $query= "UPDATE ".ADMIN_TBL." SET
			                ad_password= '". $this->encryptPassword($data['new_password'])."',
			                updated_at='$curr' WHERE id='".$_SESSION['ecom_admin_id']."' "; 
	                $exe 	= mysql_query($query);
	                if($exe){
						unset($_SESSION['change_password_key']);
						return 1;
					}else{
						return "Sorry!! Unexpected Error Occurred. Please try again.";
					}
			    }else{
			        return $this->errorMsg("Your Current Password is wrong.");
			    }
			}else{
				return "Sorry Invalid User Entry. Try Re login to the Panel to continue.";
			}
		}else{
			return "Sorry Invalid User Entry. Try Re login to the Panel to continue.";
		}
	}


	/*--------------------------------------------- 
					Settings
	----------------------------------------------*/ 	

	// Add Tax Classes

	function addTaxClasses($data)
	{
		$layout = "";
		$validate_csrf = $this->validateCSRF($data);
		if ($validate_csrf=="success") {
			$admin_id 		= $_SESSION["ecom_admin_id"];
			$curr 			= date("Y-m-d H:i:s");
			$query = "INSERT INTO ".TAX_CLASSES." SET 
						token 		= '".$this->hyphenize($data['tax_class'])."',
						tax_class 	= '".$this->cleanString($data['tax_class'])."',
						cgst 		= '".$this->cleanString($data['cgst'])."',
						sgst 		= '".$this->cleanString($data['sgst'])."',
						igst 		= '".$this->cleanString($data['igst'])."',
						status		='1',
						added_by	= '$admin_id',	
						created_at 	= '$curr',
						updated_at 	= '$curr' ";
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


	// Change Tax Status

	function changeTaxStatus($data)
	{
		$data = $this->decryptData($data);
		$info = $this -> getDetails(TAX_CLASSES,"status"," id ='$data' ");
		if($info['status'] ==1){
			$query = "UPDATE ".TAX_CLASSES." SET status='0' WHERE id='$data' ";
		}else{
			$query = "UPDATE ".TAX_CLASSES." SET status='1' WHERE id='$data' ";
		}
		$up_exe = $this->exeQuery($query);
		//return $up_exe;
		if($up_exe){
			return 1;
		}
	}

	// Get Tax item Details

	function getTaxItemDetails($data){
		$tax_id = $this->decryptData($data);
		$query = "SELECT * FROM  ".TAX_CLASSES."  WHERE id='$tax_id' ";
		$exe 	= $this->exeQuery($query);
		return $this->editPagePublish(mysqli_fetch_assoc($exe));
	}

	// Edit Tax Classes

	function editTaxClasses($data)
	{
		$layout = "";
		if(isset($_SESSION[$data['csrf_key']])){
			if($this->cleanString($data['csrf_token']) == $_SESSION[$data['csrf_key']]){
				$admin_id 		= $_SESSION["ecom_admin_id"];
				$tax_id 		= $this->decryptData($data["token"]);
				$curr 			= date("Y-m-d H:i:s");
				$query = "UPDATE ".TAX_CLASSES." SET 
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
		$info = $this -> getDetails(TAX_CLASSES,"delete_status"," id ='$data' ");
		if($info['status'] ==1){
			$query = "UPDATE ".TAX_CLASSES." SET delete_status='0' WHERE id='$data' ";
		}else{
			$query = "UPDATE ".TAX_CLASSES." SET delete_status='1' WHERE id='$data' ";
		}
		$up_exe = $this->exeQuery($query);
		//return $up_exe;
		if($up_exe){
			return 1;
		}
	} 


//---- End ----//	
		
}


?>