<?php


class Model
{
	
	/*--------------------------------------------- 
					Base Methods
	----------------------------------------------*/


	function __construct()
	{
		date_default_timezone_set("Asia/Calcutta");
	}
	
	function encryptPassword($data){
		return $encrypted = sha1($data);		
	}
	function decryptPassword($data){
		return $decrypted = sha1($data);
	}
	function encryptData($data){
		return $encrypted = base64_encode(base64_encode($data));		
	}
	function decryptData($data){
		return $decrypted1 = base64_decode(base64_decode($data));
	}
	
	function check_query($table,$column,$where){
		$connect = mysqli_connect(DB_SERVER , DB_USER, DB_PASSWORD, DB_DATABASE);
		$query = "SELECT $column FROM $table WHERE $where";
		$exe = mysqli_query($connect,$query);
		$no_rows = @mysqli_num_rows($exe);
		return $no_rows;
	}

	function getDetails($table,$column,$where){
		$connect = mysqli_connect(DB_SERVER , DB_USER, DB_PASSWORD, DB_DATABASE);
		$query = "SELECT $column FROM $table WHERE $where";
		$exe 	= mysqli_query($connect,$query);
		$rows 	= mysqli_fetch_array($exe);
		return $rows;
	}
	

	function exeQuery($query=""){
		$connect 	= mysqli_connect(DB_SERVER,DB_USER,DB_PASSWORD,DB_DATABASE);
		$rows 		= mysqli_query($connect,$query);
		return $rows;
	}


	function lastInserID($query=""){
		$connect 	= mysqli_connect(DB_SERVER , DB_USER, DB_PASSWORD, DB_DATABASE);
		$rows 		= mysqli_query($connect,$query);
		$last_id 	= mysqli_insert_id($connect);
		return $last_id;
	}

	function deleteRow($table,$where)
	{
		$q = "DELETE FROM $table WHERE $where ";
		$exe = $this->exeQuery($q);
		if($exe){
			return 1;
		}else{
			return 0;
		}
	}

	function mobileToken($length) {
	    $characters = '0123456789';
	    $charactersLength = strlen($characters);
	    $randomString = '';
	    for ($i = 0; $i < $length; $i++) {
	        $randomString .= $characters[rand(0, $charactersLength - 1)];
	    }
	    return $randomString;
	}

	// Convert URL to Array
	function convertUrlToArray($url){
		$url = str_replace(COREPATH.'product'."?", "", $url);
	    $urlExploded 	=  explode("&", $url);
	    $return 		= array();
	    foreach ($urlExploded as $param){
	        $explodedPar = explode("=", $param);
	        $return[$explodedPar[0]] = $explodedPar[1];
	    }
	    return $return;
	}

	function escapeString($data)
  	{

		$mysqli = new mysqli(DB_SERVER, DB_USER, DB_PASSWORD, DB_DATABASE);
		$escaped = $mysqli->real_escape_string($data);
		return $escaped;
  	}


	function cleanString($data)
  	{
      //$string = str_replace("'", "\'", $data);
      //$string = str_replace('"', '\"', $string);
  		//$no_slash = stripslashes($string); 
      $string = trim($data);
      $string = rtrim($string, '/');
      
      $string = $this->escapeString($string);
      $string = stripslashes($string); 
      
      $string = str_replace("\'", "''", $string);
      preg_replace("/\//", "", $string);
      return $string;
  	}

  	function publishContent($data)
  	{
  		 $string = str_replace("\'", "'", $data);
  		 $string = str_replace('\"', '"', $string);
  		 return $string;
  	}
  	 
  	function hyphenize($string) {
   		return preg_replace(
            	array('#[\\s-]+#', '#[^A-Za-z0-9\. -]+#'),
           		array('-', ''),
              urldecode(strtolower($string))
        );
	}

	function unHyphenize($string) {
   		return ucfirst(str_replace('-', " ", $string));
	}

	function uniqueToken($string) {
		$no_slash = stripslashes($string); 
		$no_space = str_replace(' ','',trim($no_slash)); 
   		return $no_space;
	}

  	// Random String

  	function generateRandomString($length) {
	    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	    $charactersLength = strlen($characters);
	    $randomString = '';
	    for ($i = 0; $i < $length; $i++) {
	        $randomString .= $characters[rand(0, $charactersLength - 1)];
	    }
	    return $randomString;
	}

	// Editpage Publish Content

	function editPagePublish($data)
 	{
	  $response = array();
	  foreach ($data as $key => $value) {
	   $response[$key] = $this->publishContent($value);
	  }
	  return $response;
 	}

 	function cleanStringData($data)
 	{
	  $response = array();
	  foreach ($data as $key => $value) {
	   $response[$key] = $this->cleanString($value);
	  }
	  return $response;
 	}
	
	// Function Error Msg

	function errorMsg($value){
		$err = "<div class='alert alert-danger ks-solid ks-active-border' role='alert'>
				    <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
				        <span aria-hidden='true' class='la la-close'></span>
				    </button>".$value."
				</div>";
		return $err;
	}

	function successMsg($value){
		$err = "<div class='alert alert-success ks-solid ks-active-border' role='alert'>
				    <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
				        <span aria-hidden='true' class='la la-close'></span>
				    </button>".$value."
				</div>";
		return $err;
	}
	
	// Get User Agent

	function get_user_agent() {
		if ( !isset( $_SERVER['HTTP_USER_AGENT'] ) )
		return '-';		
		$ua = strip_tags( html_entity_decode( $_SERVER['HTTP_USER_AGENT'] ));
		$ua = preg_replace('![^0-9a-zA-Z\':., /{}\(\)\[\]\+@&\!\?;_\-=~\*\#]!', '', $ua );			
		return substr( $ua, 0, 254 );
	}

	// Get User IP Address

	function get_IP() {
		$ip = '';
		// Precedence: if set, X-Forwarded-For > HTTP_X_FORWARDED_FOR > HTTP_CLIENT_IP > HTTP_VIA > REMOTE_ADDR
		$headers = array( 'X-Forwarded-For', 'HTTP_X_FORWARDED_FOR', 'HTTP_CLIENT_IP', 'HTTP_VIA', 'REMOTE_ADDR' );
		foreach( $headers as $header ) {
			if ( !empty( $_SERVER[ $header ] ) ) {
				$ip = $_SERVER[ $header ];
				break;
			}
		}		
		// headers can contain multiple IPs (X-Forwarded-For = client, proxy1, proxy2). Take first one.
		if ( strpos( $ip, ',' ) !== false )
			$ip = substr( $ip, 0, strpos( $ip, ',' ) );		
		return $ip;
	}	

	// Change the Date format

	function changeDateFormat($date)
	{
		$array 			= explode("/", $date);
		$new_date 		= $array[2]."/".$array[1]."/".$array[0];
		$date 			= date_create($new_date);
		$final_date		= date_format($date,"Y-m-d");
		return $final_date;
	}


	/*----------------------------------------------
				Session In and Out
	----------------------------------------------*/ 

	
	// Session In

	function sessionIn($id="",$referer="",$medium="")
	{		
		$auth_user_agent =	$this->get_user_agent();
		$auth_ip_address =	$this->get_IP();
		$curr 	= date("Y-m-d H:i:s");
		$q 		= "INSERT INTO ".ADMIN_SESSION_TBL." SET logged_id ='".$id."', auth_referer='$referer', auth_medium='$medium', auth_user_agent='$auth_user_agent', auth_ip_address='$auth_ip_address', session_in='$curr'  ";
		$exe = $this->exeQuery($q);
		if ($exe) {
			return 1;
		}else{
			return 0;
		}
	}

	// Session Out

	function sessionOut($id)
	{
		$today 	= date("Y-m-d");
		$curr 	= date("Y-m-d H:i:s");
		$info 	= $this->getDetails(ADMIN_SESSION_TBL,"id"," logged_id='".$id."' ORDER BY id DESC LIMIT 1");
		$q 		= "UPDATE ".ADMIN_SESSION_TBL." SET session_out='$curr' WHERE logged_id='".$id."' AND id='".$info['id']."'  ";
		$exe  	= $this->exeQuery($q);
		if($exe) {
			return 1;
		}else{
			return 0;
		}
	}
	
	/*--------------------------------------------- 
					Mail Functions
	----------------------------------------------*/

	// Send Email

  	function send_mail($sender_mail,$receiver_mail,$subject,$message,$bcc=""){

			$mail = new PHPMailer;
            $mail->isSMTP();
            $mail->Port = SMTP_PORT; 
            $mail->Host = MAIL_HOST;
            $mail->SMTPAuth = true;
            $mail->Username = MAIL_USERNAME;
            $mail->Password = MAIL_PASSWORD;
            //$mail->SMTPSecure = 'tls';
            $mail->From = $sender_mail;
            $mail->FromName = COMPANY_NAME;
            $mail->addAddress($receiver_mail, '');
            $mail->AddBCC($bcc, '');
            //$mail->addReplyTo(REPLY_TO, COMPANY_NAME);
            $mail->WordWrap = 50;
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body    = $message;

            if(!$mail->send()) {
			   echo 'Message could not be sent.';
			   echo 'Mailer Error: ' . $mail->ErrorInfo;
			   exit;
			}else{
				return 1;
			}
	}


	// Employee Login Email

	function employeeLoginInfo($name="",$email="",$normal_pass="") {
	    $contact_info = $this->getDetails(COMPANNY_INFO,"*"," id='1' ");
	    $layout = "<table width='100%' cellpadding='0' cellspacing='0'>
				    <tr>
				        <td style='background-color:#dddddd'>
				            <table cellpadding='0' cellspacing='0' width='600' class='table-mobile' align='center' style='width: 600px;margin: 0 auto;background-color: white; margin-top: 50px;box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);'>
				                <tr>
				                    <td height='25'></td>
				                </tr>
				                <tr>
				                    <td>
				                        <table cellpadding='0' cellspacing='0' class='table-mobile-small' align='center' style='width: 560px !important;'>
				                            <tr>
				                                <td class='header-item'>
				                                    <img width='160' src=''>
				                                </td>	
				                            </tr>
				                        </table>
				                    </td>
				                </tr>
				                <tr>
				                    <td>
				                        <table cellpadding='0' cellspacing='0' width='100%' align='center'>
				                            <tr>
				                                <td height='20'></td>
				                            </tr>
				                            <tr>
				                                <td style='border-bottom:1px solid #f8f8f8;' height='1'></td>
				                            </tr>
				                        </table>
				                    </td>
				                </tr>
				                <tr>
				                    <td style='background:#f7f7f7;padding:35px;border-top:1px solid #eeeeee;'>
				                        <table cellpadding='0' cellspacing='0' class='table-mobile-small' align='center'>
				                            <tr>
				                                <td colspan='2'>
				                                    <p style='font-family:sans-serif;font-size:22px;font-weight:bold;text-transform:none;margin-top:0;margin-bottom:10px;color:#464951;text-align:left;'>
				                                        Dear ".$name.",
				                                    </p>
				                                    <p style='font-family:sans-serif;font-size:14px;font-weight:normal;text-transform:none;margin-top:0;margin-bottom:20px;color:#464951;text-align:left;'>We have created a employee account for the company. Kindly login to the admin panel with the below credentials.<br><br>
				                                    <b>Login Details </b><br><br>
				                                    Email: ".$email."<br><br>
				                                    Password: ".$normal_pass."
				                                    </p>
				                                </td>
				                            </tr>
				                            <tr>
				                                <td align='center' bgcolor='#1e456e' style='padding:0;margin:0;line-height:1px;font-size:1px;border-radius:4px;line-height:18px'>
				                                    <a href='".BASEPATH."ecom_admin' style='text-decoration:none;border-style:none;border:0;padding:0;margin:0;font-family:Helvetica,Arial,sans-serif;font-size:16px;line-height:22px;font-weight:500;color:#ffffff;text-align:center;text-decoration:none;border-radius:4px;padding:11px 30px;border:1px solid #1e456e;display:inline-block' target='_blank'>
				                                        <strong>Click here to login</strong></a>
				                                </td>
				                            </tr>
				                        </table>
				                    </td>
				                </tr>
				                <tr>
				                    <td height='25'></td>
				                </tr>
				            </table>
				            <table cellpadding='0' cellspacing='0' class='table-mobile-small' align='center'>
				                <tr>
				                    <td style='padding:25px 0;'>
				                        <p style='font-family:sans-serif;font-size:14px;font-weight:normal;text-transform:none;margin-top:0;margin-bottom:20px;padding:0;color:#3a3d45;text-align:center;'>
				                            <a style='font-family:sans-serif;font-size:14px;font-weight:normal;text-transform:none;margin-top:0;margin-bottom:0;color:#3a3d45;text-decoration:underline;' href='".BASEPATH."aboutus'>About Us</a> |
				                            <a style='font-family:sans-serif;font-size:14px;font-weight:normal;text-transform:none;margin-top:0;margin-bottom:0;color:#3a3d45;text-decoration:underline;' href=''".BASEPATH."blog''>Blog</a> |
				                            <a style='font-family:sans-serif;font-size:14px;font-weight:normal;text-transform:none;margin-top:0;margin-bottom:0;color:#3a3d45;text-decoration:underline;' href='".BASEPATH."contact'>Contact</a>
				                        </p>
				                    </td>
				                </tr>
				            </table>
				        </td>
				    </tr>
				   </table>";
		return $layout;
		
  	}



	/*--------------------------------------------- 
					CSRF Token
	----------------------------------------------*/

	// Set CSRF 

	// Generate CSRF Token

	function getCSRF($name)
	{
		$token = $this->generateRandomString("40");
		if(!isset($_SESSION[$name])){
			$_SESSION[$name] = $token;
		}
		$input = "<input type='hidden' name='csrf_token' value='".$_SESSION[$name]."' />
					<input type='hidden' name='csrf_key' value='".$name."' /> ";
		return $input;
	}

	// Unset CSRF Token

	function unSetCSRF($name){
		unset($_SESSION[$name]);
	}

	// Validate CSRF Token

	function validateCSRF($data)
	{
		$output = "";
		if(isset($_SESSION[$data['csrf_key']])){
			if($this->cleanString($data['csrf_token']) == $_SESSION[$data['csrf_key']]){
				$output =  "success";
			}else{
				$output =  "Sorry Invalid User Entry. Try Re login to the Panel to continue.";
			}
		}else{
			$output =  "Sorry Invalid User Entry. Try Re login to the Panel to continue.";
		}
		return $output;
	}

	/*--------------------------------------------- 
				Logged User Info
	----------------------------------------------*/

	// General Logged User  info for all pages

	function userInfo()
	{	
		$today = date("Y-m-d");
		$query = "SELECT U.* FROM ".CONTRACTOR_TBL." U WHERE U.id ='".$_SESSION["ecom_contractor_id"]."' ";
		$exe = $this->exeQuery($query);;
		$list = mysqli_fetch_assoc($exe);
		$list['state_list']	    	 =  $this->getStatelist($list['state_id']);
		$list['service_tags']		 =  $this->getServiceTag($list['service_tags']);
		$list['classified_profile']  =  $this->getClassifiedProfiles($list['profile_type']);
		$list['csrf_update_profile'] =  $this->getCSRF("update_profile");

		return $list;
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

	// Check Permission

	function checkPermissionPage($token)
	{	
		if (@$_SESSION['is_super_admin']==1) {
			return 1;
		}else{
			$q ="SELECT id,permision_title,token,permission FROM ".PERMISSION_DATA_TBL." WHERE token='$token' AND status='1' ORDER BY id ASC " ;
		    $query = $this->exeQuery($q);
		    if(mysqli_num_rows($query)>0){
		    	$i=1;
		    	while($list = mysqli_fetch_array($query)){
		    		$permission = $list['permission'];
		    		return $_SESSION[$permission];
		    	}
		    }
		}
	}

	// Upload Media Image

	function uploadMediaFiles($file,$item_type,$item_id)
	{
		$curr 	= date("Y-m-d H:i:s");
		foreach ($file as $key => $value) {
			$q = "INSERT INTO ".MEDIA_TBL." SET 
				  item_type 	= '".$item_type."',
				  item_id 		= '".$item_id."',
				  file_name 	= '".$value['file_name']."',
				  file_type 	= '".$value['file_type']."',
				  file_size 	= '".$value['file_size']."',
				  created_at 	= '".$curr."',
				  updated_at 	= '".$curr."'
				" ;
			$exe = $this->exeQuery($q);
		}
	}


}


?>