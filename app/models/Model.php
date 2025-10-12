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
	
	function inrFormat($num) {
        $value = number_format((float)$num, 2, '.', '');
        return preg_replace("/(\d+?)(?=(\d\d)+(\d)(?!\d))(\.\d+)?/i", "$1,", $value);
    }

    function inrFormatFields($data) {
      $response = array();
	  foreach ($data as $key => $value) {
	   $response[$key] = $this->inrFormat($value);
	  }
	  return $response;
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

	function getProductPrice($product_id,$variant)
	{	

		// Condition for get product price based on variant

		$variant_check = "";

		if($variant!="") {
			$variant_check = "AND variant_id='".$variant['id']."' ";
		}

		if(isset($_SESSION['vendors_at_this_location']))
		{


			// Get lowest price for this product 
			if(count($_SESSION['vendors_at_this_location']) > 0) {
				$query = "SELECT id,vendor_id,selling_price,stock,min_qty FROM ".VENDOR_PRODUCTS_TBL." WHERE product_id='".$product_id."'  AND stock >= min_qty AND status='1' AND vendor_id IN (" . implode(',', array_map('intval',$_SESSION['vendors_at_this_location'])) .") ".$variant_check." ORDER BY selling_price ASC LIMIT 1 ";
				$exe    = $this->exeQuery($query);
				$result = mysqli_fetch_array($exe);
				

				if(isset($result['vendor_id'])){
					$pass_exe    = $this->exeQuery($query);
					$result  = mysqli_fetch_array($pass_exe);
				}else{
					$q 	 = "SELECT * FROM ".PRODUCT_TBL." WHERE id='".$product_id."'   ";
					$pass_exe = $this->exeQuery($q);
					$result  = mysqli_fetch_array($pass_exe);
				}


			} else {
				$q 	 = "SELECT * FROM ".PRODUCT_TBL." WHERE id='".$product_id."'   ";
				$pass_exe = $this->exeQuery($q);
				$result  = mysqli_fetch_array($pass_exe);
			}
		} else {
			$q 	 = "SELECT * FROM ".PRODUCT_TBL." WHERE id='".$product_id."'   ";
			$pass_exe = $this->exeQuery($q);
			$result  = mysqli_fetch_array($pass_exe);
		}


		return  $result;

	}

	function amountInWords($amount)
	{
	   $amount_after_decimal = round($amount - ($num = floor($amount)), 2) * 100;
	   // Check if there is any number after decimal
	   $amt_hundred = null;
	   $count_length = strlen($num);
	   $x = 0;
	   $string = array();
	   $change_words = array(0 => '', 1 => 'One', 2 => 'Two',
	     3 => 'Three', 4 => 'Four', 5 => 'Five', 6 => 'Six',
	     7 => 'Seven', 8 => 'Eight', 9 => 'Nine',
	     10 => 'Ten', 11 => 'Eleven', 12 => 'Twelve',
	     13 => 'Thirteen', 14 => 'Fourteen', 15 => 'Fifteen',
	     16 => 'Sixteen', 17 => 'Seventeen', 18 => 'Eighteen',
	     19 => 'Nineteen', 20 => 'Twenty', 30 => 'Thirty',
	     40 => 'Forty', 50 => 'Fifty', 60 => 'Sixty',
	     70 => 'Seventy', 80 => 'Eighty', 90 => 'Ninety');
	    $here_digits = array('', 'Hundred','Thousand','Lakh', 'Crore');
	    while( $x < $count_length ) {
	      $get_divider = ($x == 2) ? 10 : 100;
	      $amount = floor($num % $get_divider);
	      $num = floor($num / $get_divider);
	      $x += $get_divider == 10 ? 1 : 2;
	      if ($amount) {
	       $add_plural = (($counter = count($string)) && $amount > 9) ? 's' : null;
	       $amt_hundred = ($counter == 1 && $string[0]) ? ' and ' : null;
	       $string [] = ($amount < 21) ? $change_words[$amount].' '. $here_digits[$counter]. $add_plural.' 
	       '.$amt_hundred:$change_words[floor($amount / 10) * 10].' '.$change_words[$amount % 10]. ' 
	       '.$here_digits[$counter].$add_plural.' '.$amt_hundred;
	        }
	   else $string[] = null;
	   }
	   $implode_to_Rupees = implode('', array_reverse($string));
	   $get_paise = ($amount_after_decimal > 0) ? "And " . ($change_words[$amount_after_decimal / 10] . " 
	   " . $change_words[$amount_after_decimal % 10]) . ' Paise' : '';
	   return ($implode_to_Rupees ? $implode_to_Rupees . 'Rupees ' : '') . $get_paise;
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
		$url = str_replace(BASEPATH.'product'."?", "", $url);
	    $urlExploded 	=  explode("&", $url);
	    $return 		= array();
	    foreach ($urlExploded as $param){
	        $explodedPar = explode("=", $param);
	        $return[$explodedPar[0]] = $explodedPar[1];
	    }
	    return $return;
	}

	function build_http_query( $query ){
    $query_array = array();
    foreach( $query as $key => $key_value ){
        $query_array[] = urlencode( $key ) . '=' . urlencode( $key_value );
    }
    return implode( '&', $query_array );
	}

	function escapeString($data)
  	{

		$mysqli = new mysqli(DB_SERVER, DB_USER, DB_PASSWORD, DB_DATABASE);
		$escaped = $mysqli->real_escape_string($data);
		return $escaped;
  	}


  	function cleanString($data)
  	{
  		$string = trim($data);
      	$string = str_replace("'", "\'", $data);
      	$string = str_replace('"', '\"', $string);
  		//$no_slash = stripslashes($string); 
      
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
				    ".$value."
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
		$q 		= "INSERT INTO ".ADMIN_SESSION_TBL." SET logged_id ='".$id."', auth_referer='".$referer."', auth_medium='".$medium."', auth_user_agent='".$auth_user_agent."', auth_ip_address='".$auth_ip_address."', session_in='".$curr."'  ";
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
		$q 		= "UPDATE ".ADMIN_SESSION_TBL." SET session_out='".$curr."' WHERE logged_id='".$id."' AND id='".$info['id']."'  ";
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

		 if (SEND_EMAIL=="enabled"){
            if(!$mail->send()) {
			   echo 'Message could not be sent.';
			   echo 'Mailer Error: ' . $mail->ErrorInfo;
			   exit;
			}else{
				return 1;
			}
		}else{
			return 1;
		}
	}




	// Employee Login Email

	function employeeLoginInfo($name="",$email="",$normal_pass=""){
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

  	// Vendor Login Email

	function vendorLoginInfo($name="",$email="",$normal_pass=""){
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
				                                    <a href='".BASEPATH."ecom_vendor' style='text-decoration:none;border-style:none;border:0;padding:0;margin:0;font-family:Helvetica,Arial,sans-serif;font-size:16px;line-height:22px;font-weight:500;color:#ffffff;text-align:center;text-decoration:none;border-radius:4px;padding:11px 30px;border:1px solid #1e456e;display:inline-block' target='_blank'>
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
		$id= @$_SESSION["user_session_id"];
		$query = "SELECT id,name,mobile,email,status FROM ".CUSTOMER_TBL." WHERE id ='".$id."' ";
		$exe = $this->exeQuery($query);
		$list = mysqli_fetch_assoc($exe);
		return $list;
	}

	// Check Permission

	function checkPermissionPage($token)
	{	
		if (@$_SESSION['is_super_admin']==1) {
			return 1;
		}else{
			$q ="SELECT id,permision_title,token,permission FROM ".PERMISSION_DATA_TBL." WHERE token='".$token."' AND status='1' ORDER BY id ASC " ;
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



	// time ago

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

	// Site Sittings Filter

	function filtersiteSettings()
	{	
		$controller_name =  array();
		$q ="SELECT * FROM ".SITE_SETTINGS_TBL." WHERE status='1'" ;
	    $query = $this->exeQuery($q);
	    if(mysqli_num_rows($query)>0){
	    	$i=0;
	    	while($list = mysqli_fetch_array($query)){
	    		$comma = (($i>0) ? ", " : "" );
	    		$controller_name[] = strtolower($list['controller_name']);
	    		$i++;
	    	}
	    }
	    return $controller_name;
  	}

  	// Site Sittings Filter
  	function checkSiteSettings($string)
  	{
	  	  $array  = $this->filtersiteSettings();
	  	  $result = in_array($string,($array));
	      return $result;  
  	}

  	/*----------------------------------------------
				Location Pincode List 
	----------------------------------------------*/

	function getLocationPincodeList($input)
	{
		$layout = "";
		$query  = "SELECT * FROM ".LOCATION_TBL." WHERE (pincode LIKE '".$input."%' OR pincode LIKE '%".$input."%') AND status='1' AND delete_status='0' ";
		$exe    = $this->exeQuery($query);
		if(mysqli_num_rows($exe) > 0) {
			while ($list = mysqli_fetch_assoc($exe)) {
				$layout .= " <a><div class='menu_items select_pincode' data-option='".$this->encryptData($list['id'])."' >".$list['pincode']. " ( ".$list['location']." )"."</div></a>";
			}
		} else {
			$layout .= "No Records Found";
		}
		return $layout;
	}

  	/*----------------------------------------------
				Location List 
	----------------------------------------------*/
	function getLocationList()
  	{
  		$layout_group = "";
  		$result 	  = array();
  		$layout_array = array();
  		$q = "SELECT LG.id,LG.token,LG.group_name,LG.status,LG.delete_status,LA.location,LA.group_id FROM ".LOCATIONGROUP_TBL." LG LEFT JOIN ".LOCATION_TBL." LA ON (LA.group_id=LG.id) WHERE  LG.delete_status='0' AND LG.status='1'  AND LG.status='1'  GROUP BY LG.id ASC " ;
	    $query = $this->exeQuery($q);	
	    if(mysqli_num_rows($query) > 0){
	    	$i=0;
	    	while($details = mysqli_fetch_array($query)){
	    		$list 	   	    = $this->editPagePublish($details);
	    		$if_location_group_and_location_is_available = $this->checkLocationAndLocationGroup($list['id']);  
	    		if($if_location_group_and_location_is_available) {
		    		$layout_array[$list['id']] = $this->getLocationsForGroup($this->encryptData($list['id']));
		    		$layout_group .= "
			    		<a class='dropdown-item select_location' data-location_layout='".$i."' data-token='".$list["token"]."' data-group_name='".$list["group_name"]."'   data-option='".$this->encryptData($list['id'])."' href='javascript:void();'>".$list["group_name"]."</a>";
		    		$i++;
		    	}
	    	}
	    }

	    $result['group_layout']    = $layout_group;
	    $result['location_layout'] = $layout_array;
	    return $result;
  	}

  	// Check Location And Location Group

  	function checkLocationAndLocationGroup($city_id)
  	{
  		$location_group_ids = array();
  		$check_location_group = $this->check_query(GROUP_TBL,"id","city_id='".$city_id."'");
  		if($check_location_group) {
	  		$q     = "SELECT id FROM ".GROUP_TBL." WHERE city_id='".$city_id."' ";
	  		$exe   = $this->exeQuery($q);
	  		if(mysqli_num_rows($exe) > 0)
	  		{
	  			while ($list = mysqli_fetch_assoc($exe)) {
	  				$location_group_ids[] = $list['id'];
	  			}
	  		}

	  		$locations = $this->check_query(LOCATION_TBL,"id","group_id IN (".implode(",", $location_group_ids).") ");

	  		if($locations) {
	  			return true;
	  		} else {
	  			return false;
	  		}

	  	} else {
	  		return false;
	  	}
  	}

  	// Get locations for selected Location group

  	function getLocationsForGroup($group_id)
  	{	
		$layout         = "";
		$token          = $this->decryptData($group_id);
  		$location_group = $this->getDetails(LOCATIONGROUP_TBL,"*"," id='".$token."' ");

  		$q = "SELECT L.id,L.token,L.location,L.pincode,L.longitude,L.latitude,L.group_id,L.status,L.delete_status,G.id as group_id,G.city_id FROM ".LOCATION_TBL." L LEFT JOIN ".GROUP_TBL." G ON (G.id=L.group_id) WHERE G.city_id='".$location_group['id']."'  AND L.delete_status='0' AND L.status='1' ORDER BY L.id ASC";

	    $query = $this->exeQuery($q);	
	    if(mysqli_num_rows($query) > 0){
	    	$i=1;
	    	while($details = mysqli_fetch_array($query)) {
	    		$list 	   = $this->editPagePublish($details); 
	    		$layout .= "
		    		<a class='dropdown-item select_area' data-token='".$list["token"]."' data-location='".$list["location"]."'   data-option='".$this->encryptData($list['id'])."'  href='javascript:void();'>".$list["location"]."</a>";
	    		$i++;
	    	}
	    }
	    return $layout;
  	}


  	// Forgot password Email

	function forgotPasswordTemp($name,$user_token,$token){
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
				                                    <img width='160' style='display: block;margin: 0 auto;' src='".IMGPATH."logo.png'>
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
				                                    <p style='font-family:sans-serif;font-size:14px;font-weight:normal;text-transform:none;margin-top:0;margin-bottom:20px;color:#464951;text-align:left;'>Click the Forgot password button to reset your Sapiens password for your account. If you didn't ask to reset your password, you can ignore this email.
				                                    </p>
				                                </td>
				                            </tr>
				                            <tr>
				                                <td align='center' bgcolor='#1e456e' style='padding:0;margin:0;line-height:1px;font-size:1px;border-radius:4px;line-height:18px'>
				                                    <a href='".BASEPATH."login/reset/".$user_token."/$token' style='text-decoration:none;border-style:none;border:0;padding:0;margin:0;font-family:Helvetica,Arial,sans-serif;font-size:16px;line-height:22px;font-weight:500;color:#ffffff;text-align:center;text-decoration:none;border-radius:4px;padding:11px 30px;border:1px solid #1e456e;display:inline-block' target='_blank'>
				                                        <strong>Reset Password</strong></a>
				                                </td>
				                            </tr>
				                        </table>
				                    </td>
				                </tr>
				                <tr>
								    <td style='padding-top:20px;'>
								        <table cellpadding='0' cellspacing='0' class='table-mobile-small' align='center'>
								            <tr>
								                <td>
								        <p style='font-family:sans-serif;font-size:14px;font-weight:normal;text-transform:none;margin-top:0;margin-bottom:20px;padding:0;color:#484848;text-align:center;'>
								            <a style='font-family:sans-serif;font-size:14px;font-weight:normal;text-transform:none;margin-top:0;margin-bottom:0;color:#3a3d45;text-decoration:underline;' href='".BASEPATH."aboutus'>About Us</a> |
								            <a style='font-family:sans-serif;font-size:14px;font-weight:normal;text-transform:none;margin-top:0;margin-bottom:0;color:#3a3d45;text-decoration:underline;' href=''".BASEPATH."blog''>Blog</a> |
								            <a style='font-family:sans-serif;font-size:14px;font-weight:normal;text-transform:none;margin-top:0;margin-bottom:0;color:#3a3d45;text-decoration:underline;' href='".BASEPATH."contact'>Contact</a>
								                       
								        </p>
								                    
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
				                        
				                    </td>
				                </tr>
				            </table>
				        </td>
				    </tr>
				   </table>";
		return $layout;
		
  	}

  	function emailOtpTemp($name,$mobile_token,$account_verify=""){
  		$content = (($account_verify!="")? "Please enter below verification code for access your user account" : "Thank you for Registering with".COMPANY_NAME." .");

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
				                                    <img width='160' style='display: block;margin: 0 auto;' src='".IMGPATH."logo.png'>
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
				                                    <p style='font-family:sans-serif;font-size:14px;font-weight:normal;text-transform:none;margin-top:0;margin-bottom:20px;color:#464951;text-align:left;'> ".$content." </p>
				                                    <p>
				                                    <p style='font-family:sans-serif;font-size:16px;font-weight:normal;text-transform:none;margin-top:0;margin-bottom:20px;color:#464951;text-align:left;'>Your Verification code is : <strong>".$mobile_token."</strong></p>
				                                   
				                                </td>
				                            </tr>
				                        </table>
				                    </td>
				                </tr>
				                <tr>
				                    <td style='padding-top:20px;'>
				                        <table cellpadding='0' cellspacing='0' class='table-mobile-small' align='center'>
				                            <tr>
				                                <td>
				                        <p style='font-family:sans-serif;font-size:14px;font-weight:normal;text-transform:none;margin-top:0;margin-bottom:20px;padding:0;color:#484848;text-align:center;'>
				                            <a style='font-family:sans-serif;font-size:14px;font-weight:normal;text-transform:none;margin-top:0;margin-bottom:0;color:#3a3d45;text-decoration:underline;' href='".BASEPATH."aboutus'>About Us</a> |
				                            <a style='font-family:sans-serif;font-size:14px;font-weight:normal;text-transform:none;margin-top:0;margin-bottom:0;color:#3a3d45;text-decoration:underline;' href=''".BASEPATH."blog''>Blog</a> |
				                            <a style='font-family:sans-serif;font-size:14px;font-weight:normal;text-transform:none;margin-top:0;margin-bottom:0;color:#3a3d45;text-decoration:underline;' href='".BASEPATH."contact'>Contact</a>
				                                       
				                        </p>
				                                    
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
				                            
				                        </p>
				                    </td>
				                </tr>
				            </table>
				        </td>
				    </tr>
				   </table>";
		return $layout;
  	}

  	// Contractor Login Email

	function contractorLoginInfo($name="",$email="",$normal_pass=""){
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
				                                    <img width='160' src='".ASSETS_PATH."logo.png'>
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
				                                    <p style='font-family:sans-serif;font-size:22px;font-weight:bold;text-transform:none;margin-top:25;margin-bottom:25px;color:#464951;text-align:left;'>
				                                        Dear ".$name.",
				                                    </p>
				                                    <p style='font-family:sans-serif;font-size:14px;font-weight:normal;text-transform:none;margin-top:25;margin-bottom:25px;color:#464951;text-align:left;'>We have created a Expert account for the company. Kindly login to the admin panel with the below credentials.<br><br>
				                                    <b>Login Details </b><br><br>
				                                    Email: ".$email."<br><br>
				                                    Password: ".$normal_pass."
				                                    </p>
				                                </td>
				                            </tr>
				                            <tr>
				                                <td align='center' bgcolor='#1e456e' style='padding:0;margin:0;line-height:1px;font-size:1px;border-radius:4px;line-height:18px'>
				                                    <a href='".BASEPATH."ecom_hire' style='text-decoration:none;border-style:none;border:0;padding:0;margin:0;font-family:Helvetica,Arial,sans-serif;font-size:16px;line-height:22px;font-weight:500;color:#ffffff;text-align:center;text-decoration:none;border-radius:4px;padding:11px 30px;border:1px solid #1e456e;display:inline-block' target='_blank'>
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

  	function contactEnquiry($data){
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
				                                    <img width='160' style='display: block;margin: 0 auto;' src='".IMGPATH."logo.png'>
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
				                                        Dear admin,
				                                    </p>
				                                    <p style='font-family:sans-serif;font-size:14px;font-weight:normal;text-transform:none;margin-top:0;margin-bottom:20px;color:#464951;text-align:left;'>We have a Contact Enquiry from the customer with below Details  </p>
				                                    <p>
				                                    <p style='font-family:sans-serif;font-size:16px;font-weight:normal;text-transform:none;margin-top:0;margin-bottom:20px;color:#464951;text-align:left;'>Name : <strong>".$data['name']."</strong></p>
				                                    <p style='font-family:sans-serif;font-size:16px;font-weight:normal;text-transform:none;margin-top:0;margin-bottom:20px;color:#464951;text-align:left;'>Email : <strong>".$data['email']."</strong></p>
				                                    <p style='font-family:sans-serif;font-size:16px;font-weight:normal;text-transform:none;margin-top:0;margin-bottom:20px;color:#464951;text-align:left;'>Mobile : <strong>".$data['mobile']."</strong></p>
				                                    <p style='font-family:sans-serif;font-size:16px;font-weight:normal;text-transform:none;margin-top:0;margin-bottom:20px;color:#464951;text-align:left;'>Subject : <strong>".$data['subject']."</strong></p>
				                                    <p style='font-family:sans-serif;font-size:16px;font-weight:normal;text-transform:none;margin-top:0;margin-bottom:20px;color:#464951;text-align:left;'>Message : <strong>".$data['message']."</strong></p>
				                                   
				                                </td>
				                            </tr>
				                        </table>
				                    </td>
				                </tr>
				                <tr>
								    <td style='padding-top:20px;'>
								        <table cellpadding='0' cellspacing='0' class='table-mobile-small' align='center'>
								            <tr>
								                <td>
								        <p style='font-family:sans-serif;font-size:14px;font-weight:normal;text-transform:none;margin-top:0;margin-bottom:20px;padding:0;color:#484848;text-align:center;'>
								            <a style='font-family:sans-serif;font-size:14px;font-weight:normal;text-transform:none;margin-top:0;margin-bottom:0;color:#3a3d45;text-decoration:underline;' href='".BASEPATH."aboutus'>About Us</a> |
								            <a style='font-family:sans-serif;font-size:14px;font-weight:normal;text-transform:none;margin-top:0;margin-bottom:0;color:#3a3d45;text-decoration:underline;' href=''".BASEPATH."blog''>Blog</a> |
								            <a style='font-family:sans-serif;font-size:14px;font-weight:normal;text-transform:none;margin-top:0;margin-bottom:0;color:#3a3d45;text-decoration:underline;' href='".BASEPATH."contact'>Contact</a>
								                       
								        </p>
								                    
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
				                        
				                    </td>
				                </tr>
				            </table>
				        </td>
				    </tr>
				   </table>";
		return $layout;
  	}
  	function contractEnquiry($c_info,$data)
  	{
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
				                                    <img width='160' src='".IMGPATH."logo.png'>
				                                </td>
				                                <td class='header-item'>
				                                    <p style='font-family:sans-serif;font-size:20px;font-weight:bold;text-transform:uppercase;margin-top:0;margin-bottom:0;color:#484848;text-align:right;'>
				                                       Contract Enqiry Details
				                                    </p>
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
				                                        Dear ".$c_info['name'].",
				                                    </p>
				                                    <p style='font-family:sans-serif;font-size:14px;font-weight:normal;text-transform:none;margin-top:0;margin-bottom:20px;color:#464951;text-align:left;'>We have a Contract Enquiry from the customer with below Details  </p>
				                                    <p>
				                                    <p style='font-family:sans-serif;font-size:16px;font-weight:normal;text-transform:none;margin-top:0;margin-bottom:20px;color:#464951;text-align:left;'>Name : <strong>".$data['name']."</strong></p>
				                                    <p style='font-family:sans-serif;font-size:16px;font-weight:normal;text-transform:none;margin-top:0;margin-bottom:20px;color:#464951;text-align:left;'>Email : <strong>".$data['email']."</strong></p>
				                                    <p style='font-family:sans-serif;font-size:16px;font-weight:normal;text-transform:none;margin-top:0;margin-bottom:20px;color:#464951;text-align:left;'>Mobile : <strong>".$data['mobile']."</strong></p>
				                                    <p style='font-family:sans-serif;font-size:16px;font-weight:normal;text-transform:none;margin-top:0;margin-bottom:20px;color:#464951;text-align:left;'>Message : <strong>".$data['message']."</strong></p>
				                                   
				                                </td>
				                            </tr>
				                        </table>
				                    </td>
				                </tr>
				                <tr>
				                    <td style='padding-top:20px;'>
				                        <table cellpadding='0' cellspacing='0' class='table-mobile-small' align='center'>
				                            <tr>
				                                <td>
				                                    <p style='font-family:sans-serif;font-size:14px;font-weight:normal;text-transform:none;margin-top:0;margin-bottom:20px;padding:0;color:#484848;text-align:center;'>
				                                        Payments should be made within 30 days with one of the options below, or you can enter any note here if necessary, you have much space
				                                    </p>
				                                    <p style='font-family:sans-serif;font-size:14px;font-weight:normal;text-transform:none;margin-top:0;margin-bottom:0;padding:0;color:#484848;text-align:center;'>
				                                        <strong>Payment Methods:</strong> Cheque, PayPal, Western Union <br />
				                                        <strong>We Accept:</strong> MasterCard, Visa, American Express <br />
				                                    </p>
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

  	function customerInvoice($order_id="")
	{

		$layout ="";
		$info 		= $this->getDetails(ORDER_TBL,"*"," id='".$order_id."'");
		$address 	= $this->getDetails(CUSTOMER_ADDRESS_TBL,"*"," id='".$info['order_address']."' ");
		$contact_info = $this->getDetails(COMPANNY_INFO,"*"," id='1' ");
		if($info['coupon_code']!=""){
			$coupon_code = "( Coupon Code : ".$info['coupon_code']." Applied )";
		}else{
			$coupon_code ="";
		}

		$gst_name_data = "";
	    $gstin_number_data = "";

	    if($address['gst_name']!="") {
	    	$gst_name_data = "GST Name : ".$this->publishContent($address['gst_name']).".<br>";
	    }
	    if($address['gstin_number']!="") {
	    	$gstin_number_data ="GSTIN    : ".$this->publishContent($address['gstin_number']).".<br>";
	    }
		
		$layout .="
				<div bgcolor='#d9e8f3' style='margin:0;padding:0'>
				<table cellpadding='0' cellspacing='0' border='0' width='100%' bgcolor='#e1e8ed' style='background-color:#d9e8f3;padding:0;margin:0;line-height:1px;font-size:1px'>
					<tbody>
						<tr>
							<td align='center' height='70' style='height:70px;padding:0;margin:0;line-height:1px;font-size:1px'></td>
						</tr>
						<tr>
							<td align='center' style='padding:0;margin:0;line-height:1px;font-size:1px'>
								<table align='center' width='65%' style='padding:0;margin:0;line-height:1px;font-size:1px' bgcolor='#ffffff' cellpadding='0' cellspacing='0' border='0'>
									<tbody>
										<tr>
											<td height='10' style='line-height:1px;display:block;height:10px;padding:0;margin:0;font-size:1px'></td>
										</tr>
									</tbody>
								</table>  
								<table align='center' width='65%' style='background-color:#fff;padding:0;margin:0;line-height:1px;font-size:1px' bgcolor='#fff' cellpadding='0' cellspacing='0' border='0'>
									<tbody>
										<tr>
											<td style='padding:0;margin:0;line-height:1px;font-size:1px'>
												<table cellpadding='0' cellspacing='0' border='0' width='100%' style='width:100%;padding:0;margin:0;line-height:1px;font-size:1px' align='left'>
													<tbody>
														<tr>
															<td align='left' width='15' style='width:15px;padding:0;margin:0;line-height:1px;font-size:1px'></td>
															<td align='left' width='160' style='padding:0;margin:0;line-height:1px;font-size:1px'>
																<a href='".BASEPATH."' style='text-decoration:none;border-style:none;border:0;padding:0;margin:0;' target='_blank'>
																<img align='left' width='160px' src='".ASSETS_PATH."logo.png' style='width:160px;padding-bottom:2px;margin:0;padding:0;display:block;border:none;outline:none' class='CToWUd'></a> 
															</td>
															<td align='left' width='10' style='width:10px;padding:0;margin:0;line-height:1px;font-size:1px'></td>
															<td align='right' style='padding:0;margin:0;line-height:1px;font-size:1px;font-family:Helvetica,Arial,sans-serif;color:#66757f;font-size:16px;padding:5px;margin:0px;font-weight:300;line-height:100%;text-align:right'></td>
														</tr>	
													</tbody>
												</table> 
											</td>
										</tr>
									</tbody>
								</table>
								<table align='center' width='65%' style='background-color:#ffffff;padding:10px 0 0 0;margin:0;line-height:1px;font-size:1px' cellpadding='0' cellspacing='0' border='0'>
									<tbody>
										<tr>
											<td colspan='2' height='1' style='line-height:1px;display:block;height:1px;background-color:#e1e8ed;padding:0;margin:0;line-height:1px;font-size:1px'></td>
										</tr>
									</tbody>
								</table>
								<table align='center' width='65%' style='background-color:#ffffff;padding:0;margin:0;line-height:1px;font-size:1px' cellpadding='0' cellspacing='0' border='0'>
									<tbody>
										<tr>
											<td width='50' style='width:50px;padding:0;margin:0;line-height:1px;font-size:1px'></td>
											<td align='center' style='padding:0;margin:0;line-height:1px;font-size:1px'>
												<table align='center' width='100%' style='background-color:#ffffff;padding:0;margin:0px 0 20px 0; font-family:Helvetica,Arial,sans-serif;color:#66757f;font-size:16px'  cellpadding='10'  cellspacing='0' border='0'>
					                                <tbody>
					                                    <tr>
					                                        <td width='50%' >
					                                            <div class='invoice_to'>
					                                                <h4>Invoice to</h4>
										                            <p style='line-height:25px;'> ".$address['user_name']." <br>
					                                                    ".$address['address']."<br>
					                                                    ".$address['city']." <br>
					                                                    ".$address['district']." <br>
					                                                    Phone:".$address['mobile']." <br>
					                                                    $gst_name_data
					                                                    $gstin_number_data
					                                                </p>
					                                            </div>
					                                        </td>    
					                                        <td width='50%' >
					                                            <div class='ship_to'>
					                                                <h4>Ship to</h4>
					                                                 <p style='line-height:25px;'> ".$address['user_name']." <br>
					                                                    ".$address['address']."<br>
					                                                    ".$address['city']." <br>
					                                                    ".$address['district']." <br>
					                                                    Phone:".$address['mobile']." 
					                                                </p>
					                                                </p>
					                                                <span class='clearfix'></span>
					                                            </div>
					                                        </td>
					                                    </tr>

					                                    <tr class='invoice_info'>
					                                        <td width='50%' >
					                                            <div class='invoice_heading'>
					                                                <h2>Invoice</h2>
					                                                <p>Order Number: ".$info['order_uid']."</p>
					                                                <p>Order Date: ".date("F d, Y",strtotime($info['order_date']) )."</p>
					                                            </div>
					                                        </td>    
					                                        <td width='50%' >
					                                            <div class='invoice_price'>
					                                                <h4>Rs. ".number_format($info['total_amount']-$info['coupon_value'],2)."</h4>
					                                                <p>Thank you for your purchase!
					                                                </p>
					                                                <span class='clearfix'></span>
					                                            </div>
					                                        </td>
					                                    </tr>
					                                    <tr class='method_info'>
					                                        <td width='50%' >
					                                            <div class='payment_method'>
					                                                <h4><strong>Payment method</strong>: ".$info['payment_type']."</h4>
					                                            </div>
					                                        </td>    
					                                        <td width='50%' >
					                                            <div class='shipping_method'>
					                                                <h4><strong>Shipping Cost</strong>: ".$info['shipping_cost']."</h4>
					                                                <span class='clearfix'></span>
					                                            </div>
					                                        </td>
					                                    </tr>
					                                </tbody>
					                            </table>";

				if($info['notes']!=""){
					$layout .="
					                            <table width='100%' style='background-color:#ffffff;padding:0;margin:0px 0 20px 0; font-family:Helvetica,Arial,sans-serif;color:#66757f;font-size:16px;word-break: break-word;'  cellpadding='10'  cellspacing='0' border='0'>
					                            	<thead align='left'>
				                                        <th style='border-bottom:2px solid #ccc'>Notes</th>
				                                    </thead>
				                                    <tbody>
				                                    <tr>
														<td>
															<p style='line-height: normal;'>".$info['notes']."</p>
														</td>
													<tr>
				                                    </tbody>
					                            </table>";
			    }

			    $layout .= "
												<table  align='center' width='100%' style='background-color:#ffffff;padding:0;margin:0px 0 20px 0; font-family:Helvetica,Arial,sans-serif;color:#66757f;font-size:16px'  cellpadding='10'  cellspacing='0' border='0'>
				                                    <thead align='left'>
				                                        <th style='border-bottom:2px solid #ccc'>Product</th>
				                                        <th style='border-bottom:2px solid #ccc'>Cost</th>
				                                        <th style='border-bottom:2px solid #ccc'>Qty</th>
				                                        <th style='border-bottom:2px solid #ccc' align='right'>Total </th>
				                                    </thead>
				                                    <tbody>
				                                        ".$this->emailGetOrderDetails($order_id)."
				                                        <tr align='right' style='padding: 25px 0px;'>
				                                            <td></td>
				                                            <td></td>
				                                            <td style='border-bottom:1px solid #ccc' align='right'>Sub total</td>
				                                            <td style='border-bottom:1px solid #ccc;white-space: nowrap;'>Rs. ".number_format($info['total_amount'],2)."</td>
				                                        </tr>
				                                        <tr align='right' style='padding: 25px 0px;'>
				                                            <td></td>
				                                            <td></td>
				                                            <td style='border-bottom:2px solid #ccc'><strong>Shipping Fee</strong></td>
				                                            <td style='border-bottom:2px solid #ccc'>Rs. ".number_format($info['shipping_cost'])."</strong></td>
				                                        </tr>

				                                        <tr align='right' style='padding: 25px 0px;'>
				                                            <td></td>
				                                            <td></td>
				                                            <td style='border-bottom:1px solid #ccc;line-height: normal;white-space: nowrap;' align='right'><strong>Discount </strong>".$coupon_code."</td>
				                                            <td style='border-bottom:1px solid #ccc'> &#8722; Rs. ".number_format($info['coupon_value'])."</td>
				                                        </tr>

				                                        <tr align='right' style='padding: 25px 0px;'>
				                                            <td></td>
				                                            <td></td>
				                                            <td style='border-bottom:2px solid #ccc'><strong>Total</strong></td>
				                                            <td style='border-bottom:2px solid #ccc;white-space: nowrap;'>Rs. ".number_format($info['total_amount']+$info['shipping_cost']-$info['coupon_value'],2) ." </strong> </td>
				                                        </tr>
				                                        
				                                    </tbody>
				                                </table>
											</td>
											<td width='50' style='width:50px;padding:0;margin:0;line-height:1px;font-size:1px'></td>
										</tr>
									</tbody>
								</table>
								
								<table align='center' width='65%' style='background-color:#f6f6f6;padding:0;margin:0;line-height:1px;font-size:1px' cellpadding='0' cellspacing='0' border='0'>
									<tbody>
										<tr>
											<td height='1' style='line-height:1px;display:block;height:1px;background-color:#e1e8ed;padding:0;margin:0;line-height:1px;font-size:1px'></td>
										</tr>
										<tr>
											<td height='20' style='height:20;padding:0;margin:0;line-height:1px;font-size:1px'></td>
										</tr>
										
										<tr>
											<td align='center' style='padding:0;margin:0;line-height:1px;font-size:1px'> <span> <a style='text-decoration:none;border-style:none;border:0;padding:0;margin:0;font-family:Helvetica,Arial,sans-serif;color:#8899a6;font-size:12px;padding:0px;margin:0px;font-weight:normal;line-height:12px'>".EMAIL_FOOTER."</a> </span> </td>
										</tr>
										<tr>
											<td height='20' style='height:20px;padding:0;margin:0;line-height:1px;font-size:1px'></td>
										</tr>
										
									</tbody>
								</table>
						 	</td>
						</tr>
						<tr>
							<td align='center' height='70' style='height:70px;padding:0;margin:0;line-height:1px;font-size:1px'></td>
						</tr>
					</tbody>
				</table>
			</div>";
		return $layout;
	}


	function emailGetOrderDetails($order_id="")
    {    
        $layout = "";
        $query = "SELECT I.id,I.product_id,I.variant_id,I.order_id,I.price,I.final_price as prize,I.qty,I.size,I.sub_total,I.total_tax,I.total_amount,O.order_uid,P.product_name,P.selling_price,P.page_url,V.variant_name,(SELECT file_name FROM ".MEDIA_TBL." WHERE item_id=I.product_id AND item_type='product' AND delete_status=0 ORDER BY id ASC LIMIT 1) as  image FROM  ".ORDER_ITEM_TBL." I LEFT JOIN ".PRODUCT_TBL." P ON(P.id=I.product_id) LEFT JOIN ".ORDER_TBL." O ON (O.id=I.order_id) LEFT JOIN ".PRODUCT_VARIANTS." V ON (V.id=I.variant_id) WHERE I.order_id='".$order_id."' ORDER BY I.price*I.qty  DESC ";
        $exe = $this->exeQuery($query);
        $count = mysqli_num_rows($exe);
        if ($count > 0) {
            $i = 1;
            while ($list = mysqli_fetch_assoc($exe)) {
                $pic = (($list['image']!="") ? "<img class='tr_all_long_hover' src='".SRCIMG.$list['image']."' alt='' style='width:150px;'>" : "<img class='tr_all_long_hover' src='".ASSETS_PATH."no_img.jpg' alt='' style='width:150px;'>");
				$name   = $list['variant_id']==0 ? $list['product_name'] : $list['product_name']." - ".$list['variant_name'] ;


                $layout .="
                <tr style='padding: 25px 0px;'>
                    <td>
                        <a href='javascript:void();' class='color_dark d_inline_b m_bottom_5' style='line-height:inherit;white-space: nowrap;'>".$this->publishContent($name)."</a>
                    </td>	<td style='white-space: nowrap;'>
                        <p class='f_size_large color_dark'>Rs. ".$this->inrFormat($list['prize'])."</p>
                    </td>
                    <td>".$list['qty']."</td>
                    <td  align='right' style='white-space: nowrap;'><p class='color_dark f_size_large'>Rs. ".$this->inrFormat($list['total_amount'])."</p></td>
                </tr>	
                ";
            $i++;
            }
        }
        return $layout;
    }

  // vendor Items total in order

  function getVendorItemToltals($order_id)
	{	
		$vendor_id 	= @$_SESSION["ecom_vendor_id"];
		$query      = "SELECT vendor_invoice_number,SUM(sub_total) as subTotal, SUM(final_price) as finalPrice,SUM(sgst) as SCST,SUM(cgst) as CGST,SUM(igst) as IGST,SUM(sgst_amt) as SGST_AMT,SUM(cgst_amt) as CGST_AMT,SUM(igst_amt) as IGST_AMT,SUM(total_amount) as totalAMT,SUM(total_tax) as totalTax, SUM(vendor_commission) as vendorCommission, SUM(vendor_commission_tax) as vendorCommissionTax, SUM(vendor_payment_charge) as vendorPaymentCharge, SUM(vendor_payment_tax) as vendorPaymenTax, SUM(vendor_shipping_charge) as vendorShippingCharge, SUM(vendor_shipping_tax) as vendorShippingTax, SUM(vendor_commission_amt) as vendorCommissionAmt, SUM(vendor_commission_tax_amt) as vendorCommissionTaxAmt, SUM(vendor_payment_charge_amt) as vendorPaymentChargeAmt, SUM(vendor_payment_tax_amt) as vendorPaymentTaxAmt, SUM(vendor_shipping_charge_amt) as vendorShippingChargeAmt, SUM(vendor_shipping_tax_amt) as vendorShippingTaxAmt
					FROM ".ORDER_ITEM_TBL." WHERE  vendor_id='".$vendor_id."' AND  order_id='".$order_id."' ";
		$exe 	    = $this->exeQuery($query);
		$result     = mysqli_fetch_array($exe);
		return $result ;

	}

    // vendor order details email template

    function vendorInvoice($order_id="",$vendor_id="")
	{
		$layout ="";
		$info 		   = $this->getDetails(ORDER_TBL,"*"," id='".$order_id."'");
		$address 	   = $this->getDetails(CUSTOMER_ADDRESS_TBL,"*"," id='".$info['order_address']."' ");
		$contact_info  = $this->getDetails(COMPANNY_INFO,"*"," id='1' ");
		$vendor_order_info  = $this->getVendorItemToltals($order_id);
		if($info['coupon_code']!=""){
			$coupon_code = "( Coupon Code : ".$info['coupon_code']." Applied )";
		}else{
			$coupon_code ="";
		}
		
		$layout .="
				<div bgcolor='#d9e8f3' style='margin:0;padding:0'>
				<table cellpadding='0' cellspacing='0' border='0' width='100%' bgcolor='#e1e8ed' style='background-color:#d9e8f3;padding:0;margin:0;line-height:1px;font-size:1px'>
					<tbody>
						<tr>
							<td align='center' height='70' style='height:70px;padding:0;margin:0;line-height:1px;font-size:1px'></td>
						</tr>
						<tr>
							<td align='center' style='padding:0;margin:0;line-height:1px;font-size:1px'>
								<table align='center' width='65%' style='padding:0;margin:0;line-height:1px;font-size:1px' bgcolor='#ffffff' cellpadding='0' cellspacing='0' border='0'>
									<tbody>
										<tr>
											<td height='10' style='line-height:1px;display:block;height:10px;padding:0;margin:0;font-size:1px'></td>
										</tr>
									</tbody>
								</table>  
								<table align='center' width='65%' style='background-color:#fff;padding:0;margin:0;line-height:1px;font-size:1px' bgcolor='#fff' cellpadding='0' cellspacing='0' border='0'>
									<tbody>
										<tr>
											<td style='padding:0;margin:0;line-height:1px;font-size:1px'>
												<table cellpadding='0' cellspacing='0' border='0' width='100%' style='width:100%;padding:0;margin:0;line-height:1px;font-size:1px' align='left'>
													<tbody>
														<tr>
															<td align='left' width='15' style='width:15px;padding:0;margin:0;line-height:1px;font-size:1px'></td>
															<td align='left' width='160' style='padding:0;margin:0;line-height:1px;font-size:1px'>
																<a href='".BASEPATH."' style='text-decoration:none;border-style:none;border:0;padding:0;margin:0;' target='_blank'>
																<img align='left' width='160px' src='".ASSETS_PATH."logo.png' style='width:160px;padding-bottom:2px;margin:0;padding:0;display:block;border:none;outline:none' class='CToWUd'></a> 
															</td>
															<td align='left' width='10' style='width:10px;padding:0;margin:0;line-height:1px;font-size:1px'></td>
															<td align='right' style='padding:0;margin:0;line-height:1px;font-size:1px;font-family:Helvetica,Arial,sans-serif;color:#66757f;font-size:16px;padding:5px;margin:0px;font-weight:300;line-height:100%;text-align:right'></td>
														</tr>	
													</tbody>
												</table> 
											</td>
										</tr>
									</tbody>
								</table>
								<table align='center' width='65%' style='background-color:#ffffff;padding:10px 0 0 0;margin:0;line-height:1px;font-size:1px' cellpadding='0' cellspacing='0' border='0'>
									<tbody>
										<tr>
											<td colspan='2' height='1' style='line-height:1px;display:block;height:1px;background-color:#e1e8ed;padding:0;margin:0;line-height:1px;font-size:1px'></td>
										</tr>
									</tbody>
								</table>
								<table align='center' width='65%' style='background-color:#ffffff;padding:0;margin:0;line-height:1px;font-size:1px' cellpadding='0' cellspacing='0' border='0'>
									<tbody>
										<tr>
											<td width='50' style='width:50px;padding:0;margin:0;line-height:1px;font-size:1px'></td>
											<td align='center' style='padding:0;margin:0;line-height:1px;font-size:1px'>
												<table align='center' width='100%' style='background-color:#ffffff;padding:0;margin:0px 0 20px 0; font-family:Helvetica,Arial,sans-serif;color:#66757f;font-size:16px'  cellpadding='10'  cellspacing='0' border='0'>
					                                <tbody>
					                                    <tr class='invoice_info'>
					                                        <td width='50%' >
					                                            <div class='invoice_heading'>
					                                                <h2>Invoice</h2>
					                                                <p>Order Invoice Number: ".$info['order_uid']."</p>
					                                                <p>Vendor Invoice Number: ".$vendor_order_info['vendor_invoice_number']."</p>
					                                                <p>Order Date: ".date("F d, Y",strtotime($info['order_date']) )."</p>
					                                            </div>
					                                        </td>    
					                                        <td width='50%' >
					                                            <div class='invoice_price'>
					                                                <h4>Rs. ".number_format($info['total_amount']-$info['coupon_value'],2)."</h4>
					                                                <span class='clearfix'></span>
					                                            </div>
					                                        </td>
					                                    </tr>
					                                    <tr class='method_info'>
					                                        <td width='50%' >
					                                            <div class='payment_method'>
					                                                <h4><strong>Payment method</strong>: ".$info['payment_type']."</h4>
					                                            </div>
					                                        </td>    
					                                        <td width='50%' >
					                                            <div class='shipping_method'>
					                                                <h4><strong>Shipping Cost</strong>: ".$info['shipping_cost']."</h4>
					                                                <span class='clearfix'></span>
					                                            </div>
					                                        </td>
					                                    </tr>
					                                </tbody>
					                            </table>";

				if($info['notes']!=""){
					$layout .="
					                            <table width='100%' style='background-color:#ffffff;padding:0;margin:0px 0 20px 0; font-family:Helvetica,Arial,sans-serif;color:#66757f;font-size:16px;word-break: break-word;'  cellpadding='10'  cellspacing='0' border='0'>
					                            	<thead align='left'>
				                                        <th style='border-bottom:2px solid #ccc'>Notes</th>
				                                    </thead>
				                                    <tbody>
				                                    <tr>
														<td>
															<p style='line-height: normal;'>".$info['notes']."</p>
														</td>
													<tr>
				                                    </tbody>
					                            </table>";
			    }

			    $total         = $vendor_order_info['totalAMT'];
                $total_charges = $vendor_order_info['vendorCommissionAmt'] + $vendor_order_info['vendorPaymentTaxAmt'] + $vendor_order_info['vendorShippingChargeAmt'];

			    $layout .= "
												<table  align='center' width='100%' style='background-color:#ffffff;padding:0;margin:0px 0 20px 0; font-family:Helvetica,Arial,sans-serif;color:#66757f;font-size:16px'  cellpadding='10'  cellspacing='0' border='0'>
				                                    <thead align='left'>
				                                    	<th style='border-bottom:2px solid #ccc'>S.NO</th>
				                                        <th style='border-bottom:2px solid #ccc'>Product</th>
				                                        <th style='border-bottom:2px solid #ccc'>Cost</th>
				                                        <th style='border-bottom:2px solid #ccc'>Qty</th>
				                                        <th style='border-bottom:2px solid #ccc' align='right'>Total </th>
				                                    </thead>
				                                    <tbody>
				                                        ".$this->emailGetVendorOrderDetails($order_id,$vendor_id)."
				                                        <tr align='right' style='padding: 25px 0px;'>
				                                            <td></td>
				                                            <td></td>
				                                            <td></td>
				                                            <td style='border-bottom:1px solid #ccc' align='right'>Sub total</td>
				                                            <td style='border-bottom:1px solid #ccc;white-space: nowrap;'>Rs. ".number_format($vendor_order_info['subTotal'],2)."</td>
				                                        </tr>
				                                        <tr align='right' style='padding: 25px 0px;'>
				                                            <td></td>
				                                            <td></td>
				                                            <td></td>
				                                            <td style='border-bottom:2px solid #ccc'><strong>Shipping Fee</strong></td>
				                                            <td style='border-bottom:2px solid #ccc'>Rs. ".number_format($info['shipping_cost'])."</strong></td>
				                                        </tr>

				                                        <tr align='right' style='padding: 25px 0px;'>
				                                            <td></td>
				                                            <td></td>
				                                            <td></td>
				                                            <td style='border-bottom:1px solid #ccc;line-height: normal;white-space: nowrap;' align='right'><strong>Discount </strong>".$coupon_code."</td>
				                                            <td style='border-bottom:1px solid #ccc'> &#8722; Rs. ".number_format($info['coupon_value'])."</td>
				                                        </tr>

				                                        <tr align='right' style='padding: 25px 0px;'>
				                                            <td style='border-bottom:2px solid #ccc'></td>
				                                            <td style='border-bottom:2px solid #ccc'></td>
				                                            <td style='border-bottom:2px solid #ccc'></td>
				                                            <td style='border-bottom:2px solid #ccc'><strong>Total</strong></td>
				                                            <td style='border-bottom:2px solid #ccc;white-space: nowrap;'>Rs. ".number_format($vendor_order_info['totalAMT']+$info['shipping_cost']-$info['coupon_value'],2) ." </strong> </td>
				                                        </tr>
				                                         <tr  style='padding: 25px 0px;'>
				                                            <td style='border-bottom:2px solid #ccc' colspan='2'><strong>Sapiens Commission & Charges</strong></td>
				                                            <td style='border-bottom:2px solid #ccc'></td>
				                                            <td style='border-bottom:2px solid #ccc'></td>
				                                            <td style='border-bottom:2px solid #ccc'></td>
				                                        </tr>
				                                         <tr align='right' style='padding: 25px 0px;'>
				                                            <td ></td>
				                                            <td ></td>
				                                            <td ></td>
				                                            <td style='border-bottom:2px solid #ccc'><strong>Sapiens Commission:</strong></td>
				                                            <td style='border-bottom:2px solid #ccc;white-space: nowrap;'>Rs. ".number_format($vendor_order_info['vendorCommissionAmt'],2)." </strong> </td>
				                                        </tr>
				                                         <tr align='right' style='padding: 25px 0px;'>
				                                            <td ></td>
				                                            <td ></td>
				                                            <td ></td>
				                                            <td style='border-bottom:2px solid #ccc'><strong>Payment Gateway Charge:</strong></td>
				                                            <td style='border-bottom:2px solid #ccc;white-space: nowrap;'>Rs. ".number_format($vendor_order_info['vendorPaymentTaxAmt'],2)." </strong> </td>
				                                        </tr>
				                                         <tr align='right' style='padding: 25px 0px;'>
				                                            <td ></td>
				                                            <td ></td>
				                                            <td ></td>
				                                            <td style='border-bottom:2px solid #ccc'><strong>Shipping Charge:	</strong></td>
				                                            <td style='border-bottom:2px solid #ccc;white-space: nowrap;'>Rs. ".number_format($vendor_order_info['vendorShippingChargeAmt'],2)." </strong> </td>
				                                        </tr>
				                                         <tr align='right' style='padding: 25px 0px;'>
				                                            <td ></td>
				                                            <td ></td>
				                                            <td ></td>
				                                            <td style='border-bottom:2px solid #ccc'><strong>Sub Total:</strong></td>
				                                            <td style='border-bottom:2px solid #ccc;white-space: nowrap;'>Rs. ".number_format($total,2)." </strong> </td>
				                                        </tr>
				                                         <tr align='right' style='padding: 25px 0px;'>
				                                            <td ></td>
				                                            <td ></td>
				                                            <td ></td>
				                                            <td style='border-bottom:2px solid #ccc'><strong>Total Charge:</strong></td>
				                                            <td style='border-bottom:2px solid #ccc;white-space: nowrap;'>Rs. ".number_format($total_charges,2)." </strong> </td>
				                                        </tr>
				                                         <tr align='right' style='padding: 25px 0px;'>
				                                            <td ></td>
				                                            <td ></td>
				                                            <td ></td>
				                                            <td style='border-bottom:2px solid #ccc'><strong>Vendor Payable:	</strong></td>
				                                            <td style='border-bottom:2px solid #ccc;white-space: nowrap;'>Rs. ".number_format($total - $total_charges,2)." </strong> </td>
				                                        </tr>
				                                        
				                                    </tbody>
				                                </table>
											</td>
											<td width='50' style='width:50px;padding:0;margin:0;line-height:1px;font-size:1px'></td>
										</tr>
									</tbody>
								</table>
								
								<table align='center' width='65%' style='background-color:#f6f6f6;padding:0;margin:0;line-height:1px;font-size:1px' cellpadding='0' cellspacing='0' border='0'>
									<tbody>
										<tr>
											<td height='1' style='line-height:1px;display:block;height:1px;background-color:#e1e8ed;padding:0;margin:0;line-height:1px;font-size:1px'></td>
										</tr>
										<tr>
											<td height='20' style='height:20;padding:0;margin:0;line-height:1px;font-size:1px'></td>
										</tr>
										
										<tr>
											<td align='center' style='padding:0;margin:0;line-height:1px;font-size:1px'> <span> <a style='text-decoration:none;border-style:none;border:0;padding:0;margin:0;font-family:Helvetica,Arial,sans-serif;color:#8899a6;font-size:12px;padding:0px;margin:0px;font-weight:normal;line-height:12px'>".EMAIL_FOOTER."</a> </span> </td>
										</tr>
										<tr>
											<td height='20' style='height:20px;padding:0;margin:0;line-height:1px;font-size:1px'></td>
										</tr>
										
									</tbody>
								</table>
						 	</td>
						</tr>
						<tr>
							<td align='center' height='70' style='height:70px;padding:0;margin:0;line-height:1px;font-size:1px'></td>
						</tr>
					</tbody>
				</table>
			</div>";
		return $layout;
	}


	function emailGetVendorOrderDetails($order_id="",$vendor_id="")
    {    
        $layout = "";
        $query  ="SELECT O.id,O.user_id,O.variant_id,O.cart_id,O.product_id,O.coupon_value,O.variant_id,O.order_id,O.category_id,O.sub_category_id,O.price,O.tax_amt,O.tax_type,O.final_price,O.vendor_commission_tax,O.vendor_commission_tax_amt,O.vendor_payment_tax,O.vendor_payment_tax_amt,O.vendor_shipping_tax,O.vendor_shipping_tax_amt,O.qty,O.sub_total,O.total_tax,O.sgst,O.cgst,O.igst,O.sgst_amt,O.cgst_amt,O.igst_amt,O.vendor_payment_charge,O.vendor_payment_charge_amt,O.vendor_shipping_charge,O.vendor_shipping_charge_amt,O.total_amount,O.vendor_commission,O.vendor_commission_amt,P.product_uid,P.product_name,P.page_url,V.variant_name FROM ".ORDER_ITEM_TBL." O LEFT JOIN ".PRODUCT_TBL." P ON (P.id=O.product_id) LEFT JOIN ".PRODUCT_VARIANTS." V ON (O.variant_id=V.id) WHERE O.order_id=$order_id AND O.vendor_id='".$vendor_id."' ORDER BY O.price*O.qty  DESC";
        $exe = $this->exeQuery($query);
        $count = mysqli_num_rows($exe);
        if ($count > 0) {
            $i = 1;
            while ($rows = mysqli_fetch_assoc($exe)) {
                	$list 	= $this->editPagePublish($rows);
					$name   = $list['variant_id']==0 ? $list['product_name'] : $list['product_name']." - ".$list['variant_name'] ;
					$layout .="
                                <tr class='marginBottom'>
                                    <td>".$i."</td>
                                    <td><a href='".BASEPATH."product/details/".$list['page_url']."' target='_blank'>".$name."</a>
                                    	<p>SGST  <span>: ".intval($list['sgst'])."% (₹  ".$this->inrFormat($list['sgst_amt']).")</span></p>
                                    	<p>CGST <span>: ".intval($list['cgst'])."% (₹  ".$this->inrFormat($list['cgst_amt']).")</span></p>
                                        <p>Commission <span> :<br> ".$list['vendor_commission']."% (₹  ".$this->inrFormat($list['vendor_commission_amt']).")- Tax ".$list['vendor_commission_tax']."% (₹ ".$list['vendor_commission_tax_amt'].")</span></p>
                                        <p>Payment Gateway Charge <span> :<br> ".$list['vendor_payment_charge']."% (₹  ".$this->inrFormat($list['vendor_payment_charge_amt']).")- Tax ".$list['vendor_payment_tax']."% (₹ ".$list['vendor_payment_tax_amt'].")</span></p>
                                        <p>Shipping Charge <span> :<br> ".$list['vendor_shipping_charge']."% (₹  ".$this->inrFormat($list['vendor_shipping_charge_amt']).")- Tax ".$list['vendor_shipping_tax']."% (₹ ".$list['vendor_shipping_tax_amt'].")</span></p>
                                    </td>
                                    <td>₹ ".$this->inrFormat($list['price'])."</td>
                                    <td>".$list['qty']."</td>
                                    <td>₹ ".$this->inrFormat($list['sub_total'])."</td>
                                </tr>
                                                
							  ";
				$i++;
            }
        }
        return $layout;
    }

  	function siteInfo($page_token='')
	{
		$result                = array();
		$today                 = date("Y-m-d");
		$id                    = @$_SESSION["user_session_id"];
		$query                 = "SELECT * FROM ".COMPANNY_INFO." WHERE 1 ";
		$exe                   = $this->exeQuery($query);
        $list                  = mysqli_fetch_array($exe);
		$seo                   = $this->getDetails(SEO_TBL,"*","page_token='$page_token'");
		$result['contact']     = $list;
		$result['seo']         = $seo;
		return $result;
	}

	function menuItems()
	{
		$result                    = array();
		$result['category']        = $this->getMenuCatgories();
		$result['cat_and_subcat']  = $this->getMenuCatgoriesAndSubcategories();
		$result['hire_list']       = $this->getHireMenuList();
		$result['brand'] 	       = $this->getBrandlist();
		$result['shop_list']       = $this->getShopBannerList();
		$result['cus_name']        = $this->getCustomerName();
		$result['notification']    = $this->getNotifications(); 

		return $result;
	}

	function getMenuCatgoriesAndSubcategories()
	{
		$layout = "";
		$q = "SELECT * FROM ".MAIN_CATEGORY_TBL." WHERE is_draft='0' AND delete_status='0' AND status='1' GROUP BY id ORDER BY  category  ASC";
		$query = $this->exeQuery($q);
	    if(mysqli_num_rows($query) > 0){
	    	$i=1;
	    	while($details = mysqli_fetch_array($query)){
				$list 			= $this->editPagePublish($details);
				$sub_cat_list	= $this->getMenuSubcategories($list['id']);
				$check_category_products = $this->check_query(PRODUCT_TBL,"*"," main_category_id='".$list['id']."' AND category_type='main'  AND status='1'  AND is_draft='0' AND delete_status='0'  ");


				if($sub_cat_list!="") {
            		$layout .= "
            			<div class='dropdown-submenu'>
							<a href='".BASEPATH."product/category/".$list['page_url']."' class='submenu-toggle'>".$list['category']." <i class='fas fa-chevron-right'></i></a>
							<div class='dropdown-menu'>
								".$sub_cat_list."
							</div>
						</div>";
	    			$i++;
	    		} else {
	    			// $layout .= "
		    		// 	<li class='menu_item_children categorie_list'><a href='".BASEPATH."product/category/".$list['page_url']."'>".$list['category']." </a>
		      //           </li>";
	    		}
		    }
	    }

	    return $layout;
	}

	function getMenuSubcategories($category)
	{	
		$layout ="";
		$q = "SELECT * FROM ".SUB_CATEGORY_TBL." WHERE category_id='".$category."' AND is_draft='0' AND delete_status='0' AND status='1' ORDER BY  subcategory  DESC ";
		$query = $this->exeQuery($q);
	    if(mysqli_num_rows($query) > 0){
	    	$i=1;
	    	while($details = mysqli_fetch_array($query)){
				$list 			= $this->editPagePublish($details);
				$pic  			= $list['file_name']!="" ? SRCIMG.$list['file_name']: ASSETS_PATH."no_img.jpg";
				$check_category_products = $this->check_query(PRODUCT_TBL,"*"," sub_category_id='".$list['id']."' AND category_type='sub' AND status='1'  AND is_draft='0' AND delete_status='0'   ");
				// ".$this->getProducts($list['id'])." 
				if($check_category_products) {
	            	$layout .= "
							<a href='".BASEPATH."product/subcategory/".$list['page_url']."'>".$list['subcategory']."</a>
	                       	";
		    			$i++;
				}
	    	}
	    }
	    return $layout;
	}

	function getHireMenuList()
	{
		$layout = "";
  		$q 		= "SELECT * FROM ".CONTRACTOR_PROFILE_TBL." WHERE status='1' AND delete_status='0' " ;
  		$query = $this->exeQuery($q);

  		$all_profiles = $this->getDetails(CONTRACTOR_TBL,"group_concat(profile_type) as rowsOfIds","invite_status='1' ");
  		$profile_ids  = explode(",", $all_profiles['rowsOfIds']);

	    if(@mysqli_num_rows($query)>0){
	    	$i=0;
	    	while($list = mysqli_fetch_array($query)){

	    		$check_if_empty = in_array($list['id'], $profile_ids);
				if($check_if_empty) {
					$layout.= "<li class='menu_item_children hire_list'><a  href='".BASEPATH."hire/hirelist/".$list['token']."' >".$list['profile']."</a></li>";
				}
                $i++;
	    	}
	    }
	    return $layout;
	}

	function getCustomerName()
	{
		if(isset($_SESSION['user_session_id'])){
			$customer_details 	= $this->getDetails(CUSTOMER_TBL,"name"," id='".$_SESSION['user_session_id']."' ");
			return $customer_details['name'];
		}
	}

	function getShopBannerList()
	{
		$layout ="";
		$q = "SELECT * FROM ".MAIN_CATEGORY_TBL." WHERE  is_draft='0' AND delete_status='0' AND status='1' ORDER BY  category  ASC";
		$query = $this->exeQuery($q);
	    if(mysqli_num_rows($query) > 0){
	    	$i=1;
	    	while($details = mysqli_fetch_array($query)){
				$list 			= $this->editPagePublish($details);
				$pic  			= $list['file_name']!="" ? SRCIMG.$list['file_name']: ASSETS_PATH."no_img.jpg";
				$sub_cat_list   = $this->getShopBannerSubcategory($list['id']);

				if($sub_cat_list!="") {
					$layout .=" <li class='menu-item-has-children'>
								    <a href='".BASEPATH."product/category/".$list['page_url']."'>".$list['category']."</a>
								    <ul class='sub-menu'><li >
								    	".$sub_cat_list."
                    			    </ul>
								</li>";
				} else {
					$layout .= "
        			 <li ><a href='".BASEPATH."product/category/".$list['page_url']."'>".$list['category']."</a>
                    </li>";
				}

        		
		    }
	    }
	    return $layout;
	}

	function getShopBannerSubcategory($category)
	{	
		$layout ="";
		$q = "SELECT * FROM ".SUB_CATEGORY_TBL." WHERE category_id='".$category."' AND is_draft='0' AND delete_status='0' AND status='1' ORDER BY  subcategory  DESC ";
		$query = $this->exeQuery($q);
	    if(mysqli_num_rows($query) > 0){
	    	$i=1;
	    	while($details = mysqli_fetch_array($query)){
				$list 			= $this->editPagePublish($details);
				$pic  			= $list['file_name']!="" ? SRCIMG.$list['file_name']: ASSETS_PATH."no_img.jpg";
				$is_empty		= $this->getProducts($list['id']);
				if($is_empty!="") {
	            	$layout .= "
	            			<li ><a href='".BASEPATH."product/subcategory/".$list['page_url']."'>".$list['subcategory']."</a>
	                        </li>
	                       ";
		    			$i++;
				}
	    	}
	    }
	    return $layout;
	}

	function getBrandlist()
  	{	
  		$layout = "";
		$q = "SELECT *  FROM ".BRAND_TBL." WHERE is_draft='0' AND delete_status='0' AND status='1' AND is_draft='0' ORDER BY id DESC " ;
	    $query = $this->exeQuery($q);	
	    if(mysqli_num_rows($query) > 0){
	    	$i=1;
	    	while($details = mysqli_fetch_array($query)){
	    		$list 	   = $this->editPagePublish($details);
	    		$is_empty  = $this->getDetails(PRODUCT_TBL,'id'," brand_id='".$list['id']."' AND is_draft='0' AND delete_status='0' AND status='1' AND is_draft='0'  ");
		    	if($is_empty) {
		    		$layout .= 
		    				"
	                          <li><a href='".BASEPATH."product/brand/".$list['page_url']."'>".$list['brand_name']."</a></li>
		    				";
		    		$i++;
		    	}
	    	}
	    }
	    return $layout;
  	}

	function getMenuCatgories()
	{
		$layout ="";
		$layout2 = "";
		$q = "SELECT * FROM ".MAIN_CATEGORY_TBL." WHERE is_draft='0' AND delete_status='0' AND status='1' ORDER BY  category  ASC";
		$query = $this->exeQuery($q);
	    if(mysqli_num_rows($query) > 0){
	    	$i=1;
	    	while($details = mysqli_fetch_array($query)){
				$list 			= $this->editPagePublish($details);
				$pic  			= $list['file_name']!="" ? SRCIMG.$list['file_name']: ASSETS_PATH."no_img.jpg";
				$is_empty		= $this->getSubcategory($list['id']);
				$check_category_products = $this->check_query(PRODUCT_TBL,"*"," main_category_id='".$list['id']."' AND category_type='main'  ");

				if($is_empty!="") {
            		$layout .= "
            			 <li class='menu_item_children categorie_list'><a href='".BASEPATH."product/category/".$list['page_url']."'>".$list['category']." <i class='fas fa-angle-right'></i></a>
                            <ul class='categories_mega_menu column'>
                                ".$this->getSubcategory($list['id'])."
                                <li class='menu_item_children last_child'>
                                    <div class='categorie_banner'>
                                    </div>
                                </li>
                            </ul>
                        </li>";
	    			$i++;
	    		} elseif ($check_category_products) {
	    			$layout2 .= "
            			 <li class='menu_item_children categorie_list'><a href='".BASEPATH."product/category/".$list['page_url']."'>".$list['category']." </a>
                        </li>";
	    		}
		    }
	    }

	    $layout = $layout . $layout2;
	    return $layout;
	}

	function getSubcategory($category)
	{	
		$layout ="";
		$q = "SELECT * FROM ".SUB_CATEGORY_TBL." WHERE category_id='".$category."' AND is_draft='0' AND delete_status='0' AND status='1' ORDER BY  subcategory  DESC ";
		$query = $this->exeQuery($q);
	    if(mysqli_num_rows($query) > 0){
	    	$i=1;
	    	while($details = mysqli_fetch_array($query)){
				$list 			= $this->editPagePublish($details);
				$pic  			= $list['file_name']!="" ? SRCIMG.$list['file_name']: ASSETS_PATH."no_img.jpg";
				$is_empty		= $this->getProducts($list['id']);
				// ".$this->getProducts($list['id'])." 
				if($is_empty!="") {
	            	$layout .= "
	            			<li class='menu_item_children'><a href='".BASEPATH."product/subcategory/".$list['page_url']."'>".$list['subcategory']."</a>
	                            <ul class='categorie_sub_menu'>
	                               
	                            </ul>
	                        </li>
	                       ";
		    			$i++;
				}
	    	}
	    }
	    return $layout;
	}

	function getNotifications()
	{
		$result = array();

		$layout = "";

		$query = "SELECT O.id,O.total_amount,O.sub_total,O.igst_amt,O.order_address,O.shipping_cost,O.coupon_value,O.order_uid,O.payment_type,O.payment_status,O.order_date,O.cancel_status,O.shipping_status,O.deliver_status,O.replaced_order,O.created_at,OA.area_name FROM ".ORDER_TBL." O LEFT JOIN ".CUSTOMER_ADDRESS_TBL." OA ON (OA.id=O.order_address) WHERE O.user_id='".@$_SESSION['user_session_id']."'   ORDER BY O.id  DESC";

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


		    	if($list['coupon_value']!="" && $list['coupon_value']!=NUll) {
		    		$coupon_value = $list['coupon_value'];
		    	} else {
		    		$coupon_value = 0;
		    	}

				$total = $list['sub_total'] + $list['igst_amt'] + $list['shipping_cost'] - $coupon_value;

				$layout .="
						<li><a href='".BASEPATH."myaccount/orderitem/".$list['id']."'><span class='fw-bold text-decoration-underline'>Order-".$list['order_uid']."</span>".$this->orderItemswe($list['id'])."</a></li>
					";
			$i++;
			}
 		}
 		$result['layout'] = $layout;
 		$result['count']  = mysqli_num_rows($exe);

 		return $result;
	}

	function orderItemswe($order_id) 
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


						$layout .="<div class='card-body myorder_cart_body'>
										<div class='row'>
											<div class='col-md-2'>
										       <img src='".$product_image."' width=80 />
										    </div>
										    <div class='col-md-6'>
										      <span class='prd_name'>".$name."</span><br>
										      <span class='prd_info'>Category : ".$category."</span><br>
										      <span class='prd_info'>Quantity : ".$list['qty']."</span><br>
										      <span class='prd_info'>Sold By : ".$info['company']."</span><br>
										    </div>
										    <div class='col-md-4'>
										     	<span class='float-end mt-4 pt-3'>".$return_button."</span>
										    </div>
									    </div>
									</div>";
						if($i<$prd_count) {			
							$layout .="<hr>";
						}
						$i++;
			}
		}
		return $layout;
	}

	function getProducts($sub_category)
	{	
		$layout ="";
		$q = "SELECT * FROM ".PRODUCT_TBL." WHERE sub_category_id='".$sub_category."' AND is_draft='0' AND delete_status='0' AND status='1' ORDER BY  product_name  DESC LIMIT 6";
		$query = $this->exeQuery($q);
	    if(mysqli_num_rows($query) > 0){
	    	$i=1;
	    	while($details = mysqli_fetch_array($query)){
			$list 			= $this->editPagePublish($details);
            $layout .= "
            			<li><a href='".BASEPATH."product/details/".$list['page_url']."'>".$list['product_name']."</a></li>
                       ";
	    			$i++;
	    	}
	    }
	    return $layout;
	}

	function cartInfo()
	{ 
		$today = date("Y-m-d");
		$user_id= @$_SESSION["user_session_id"];
		$cart_id= @$_SESSION["user_cart_id"];

		// wishlist Products 

		$check 		= $this->check_query(WISHLIST_TBL,"user_id","user_id='".@$_SESSION['user_session_id']."' ");

		// Cart ptoducts

		$query      = "SELECT product_id FROM ".CART_ITEM_TBL." WHERE user_id='".$user_id."' AND cart_id='".$cart_id."' ";
		$exe 		= $this->exeQuery($query);
		$list 		= mysqli_num_rows($exe);
		$cart_product_ids = array();

		if(mysqli_num_rows($exe)) {
			while ($list = mysqli_fetch_assoc($exe)) {
				$cart_product_ids[] = $list['product_id']; 
			}
		}

		$query      = "SELECT COUNT(cart_id) as total_items,SUM(total_amount) as total FROM ".CART_ITEM_TBL." WHERE user_id='".$user_id."' AND cart_id='".$cart_id."' ";
		$exe 		= $this->exeQuery($query);
		$list 		= mysqli_fetch_assoc($exe);

		$result 						= array();
		$result['cart'] 				= $list;
		$result['cart_product_ids'] 	= $cart_product_ids;
		$result['cart_layout'] 			= $this->headercartProducts();
		$result['wishlist'] 			= $check;
		return $result; 
	}

	function headercartProducts()
	{	
		$layout = "";
		if(isset($_SESSION['user_cart_id'])) { 
			$cart_id = @$_SESSION["user_cart_id"];
			$query = "SELECT C.id,C.user_id,C.final_price,C.qty,C.cart_id,C.product_id,P.page_url,P.category_type,P.main_category_id,P.sub_category_id,C.variant_id, C.final_price, C.total_amount, C.qty,P.product_unit,P.product_name,P.selling_price,V.variant_name,VI.company,PU.product_unit,
					(SELECT file_name FROM ".MEDIA_TBL." WHERE item_id=C.product_id AND item_type='product' AND delete_status=0 ORDER BY id ASC LIMIT 1) as product_image 
					FROM ".CART_ITEM_TBL." C LEFT JOIN ".PRODUCT_TBL." P ON (P.id=C.product_id) 
											 LEFT JOIN ".CART_TBL." CT ON(CT.id=$cart_id) 
											 LEFT JOIN ".VENDOR_TBL." VI ON (C.vendor_id=VI.id) 
											 LEFT JOIN ".PRODUCT_VARIANTS." V ON (C.variant_id=V.id)  
											 LEFT JOIN ".PRODUCT_UNIT_TBL." PU ON (PU.id=P.product_unit)  
					WHERE C.cart_id='".$cart_id."' ORDER BY C.created_at DESC ";
			$exe = $this->exeQuery($query);
			$count = mysqli_num_rows($exe) ;
			if (mysqli_num_rows($exe) > 0) {
				$i = 1;
				while ($list = mysqli_fetch_assoc($exe)) {
				if($i <=3){
				$pic  	  = $list['product_image']!='' ? SRCIMG.$list['product_image'] : ASSETS_PATH."no_img.jpg" ;
				
				if($list['category_type']=='main') {
					$cat_info = $this->getDetails(MAIN_CATEGORY_TBL,"category"," id='".$list['main_category_id']."' ");
					$cat_name = $cat_info['category'];
				} else {
					$cat_info = $this->getDetails(SUB_CATEGORY_TBL,"subcategory"," id='".$list['sub_category_id']."' ");
					$cat_name = $cat_info['subcategory'];
				}

				$product_unit = (($list['product_unit']!="")? "( ".$list['product_unit']." )" : "" );

				$name     = $list['variant_id']==0 ? $list['product_name'] : $list['product_name']." - ".$list['variant_name'] ;
				$layout  .= "
						 <div class='cart_item'>
			                <div class='cart_img'>
			                    <a href='".BASEPATH."product/details/".$list['page_url']."'><img src='".$pic."' alt=''></a>
			                </div>
			                <div class='cart_info'>
			                    <a href='".BASEPATH."product/details/".$list['page_url']."'>".$name."</a>

			                    <span class='quantity'>Category: ".$cat_name."</span>
			                    <span class='quantity'>Qty: ".$list['qty'] ." ".$product_unit."</span>
			                    <span class='price_cart'>₹".$this->inrFormat($list['final_price'])."</span>
			                    <span class='quantity'>Sold By : ".$list['company']."</span>

			                </div>
			                <div class='cart_remove'>
			                    <a href='#' class='cartItemRemove' data-variant='".$list['variant_id']."' data-id='".$this->encryptData($list['id'])."'><i class='ion-android-close '></i></a>
			                </div>
			             </div>";
		    		$i++;
				}
			}
	 		} else {
	 			$layout .= "<div class='cart_content'>Your cart is empty !</div>";
	 		}
 		} else {
	 			$layout .= "<div class='cart_content'>Your cart is empty !</div>";
	 	}
	 	return $layout;

	}

	function getLegalPages()
	{	
		$layout = "";
		$query = "SELECT * FROM ".LEGAL_PAGE_TBL." WHERE 1 AND is_draft='0' AND status='1' AND delete_status='0' ORDER BY sort_order ASC ";
		$exe =  $this->exeQuery($query);
		if (mysqli_num_rows($exe) > 0) {
			$i = 1;
			while ($list = mysqli_fetch_assoc($exe)) {
			$layout .="
					<li><a href='".BASEPATH."pages/details/".$list['page_url']."'>".$list['title']."</a></li>
					";
			$i++;
			}
 		}
 		return $layout;
	}

	// Export Invoice

	function vendorInvoicePdf($token,$products){

	    $order_item_info = $this->getDetails(ORDER_ITEM_TBL,"*"," id='".$token."' ");
        $order_info 	 = $this->getDetails(ORDER_TBL,"*"," id='".$order_item_info['order_id']."' ");
        $vendor_info 	 = $this->getDetails(VENDOR_TBL,"*"," id='".$order_item_info['vendor_id']."' ");
        $order_address 	 = $order_info['order_address'];
        $ship_address  	 = $this->getDetails(CUSTOMER_ADDRESS_TBL,"*"," user_id='".$_SESSION['user_session_id']."' AND id='".$order_address."' ");
        $cus_info        = $this->getDetails(CUSTOMER_TBL,"*","id='".$order_item_info['user_id']."' ");

    $gstname = ($ship_address['gst_name']!="")? 'GST name : '.$ship_address['gst_name']."" : "";
		$gstno = ($ship_address['gstin_number']!="")? 'GSTIN : '.$ship_address['gstin_number']."" : "";

		$file_name = $order_info['order_uid'];


		$layout = '';  
		$layout .= '  
			<style>
			table, tr, td {
			padding: 15px;
			}
			</style>

			<table style="background-color: #f3f0ec; color: #fff;">
				<tbody>
					<tr style="color: black;"> 
						<td><h3>Tax invoice/Bill of Supply/Cash memo</h3>
						Invoice Number: '.$order_item_info['vendor_invoice_number'].' / Date: '.date('d-m-Y', strtotime($order_info['created_at'])).'</td>
						<td align="right">Sapiens Order Id: '.$order_info['order_uid'].'</td>
					</tr>
				</tbody>
			</table>

			<table style="padding:20px 20px 20px 25px;">
				<tbody>
					<tr>
						<td><strong>Sold By: '.$vendor_info['company'].'</strong><br/>
						'.$vendor_info['address'].'<br/>
						'.$vendor_info['city'].', ' .$vendor_info['state_name']  .' - '.  $vendor_info['pincode'].'<br/>
						GSTIN: '.$vendor_info['gst_no'].'</td>
					</tr>
				</tbody>
			</table>    

			<table style="padding:0px 20px 20px 25px;">
				<tbody>
					<tr>
						<td><strong>Shipping Address: '.$ship_address['user_name'].' </strong><br/>
						'.$ship_address["address"].', <br/>
						'.$ship_address["city"].' ,  '.$ship_address["state_name"].'  -  '.$ship_address["pincode"].'. <br/>
						'.$this->publishContent($gstname).' <br/> '.$gstno.'
						</td>
						<td style="float:right;">
						<strong>Billing Address:  '.$ship_address['user_name'].' </strong><br/>
						'.$ship_address["address"].', <br/>
						'.$ship_address["city"].' ,  '.$ship_address["state_name"].'  -  '.$ship_address["pincode"].'. <br/>
						'.$this->publishContent($gstname).' <br/> '.$gstno.'
						</td>
					</tr>
				</tbody>
			</table>
			
			<table style="padding:20px 35px; border-top:1px solid #222;" cellpadding="10" cellspacing="0" border="0">
			<thead style="background-color: #f3f0ec;">
				<tr style="font-weight:bold;">
					<th style="text-align: center;">S.No</th>
					<th style="text-align: left;">Item name</th>
					<th style="text-align: center;">Unit Price</th>
					<th style="text-align: center;">Quantity</th>
					<th style="text-align: center;">Tax rate & Type</th>
					<th style="text-align: center;">Tax Amount</th>
					<th style="text-align: right;">Total Amount</th>
				</tr>
			</thead>
				<tbody>
					'.$products.'
				</tbody>
			</table>
			';  
			// <th style="text-align: center;">Tax Amount</th>
			//$content .= fetch_data();  
			$result = array();
			$result['layout'] = $layout;
			$result['file_name'] = ".$file_name.";
			return $result;
	}

	/*--------------------------------------------- 
				Filter Functions
	----------------------------------------------*/

	// Dynamic Sub Category Filter List

	function subcategoryFilter($filter_array,$sub_category_id="")
	{
		$layout = "";
	 	$query = "SELECT FS.id, FS.group_id, G.filter_group_name, G.token  FROM ".FILTER_GROUP_VS_SUB_CATEGORY_TBL." FS
			LEFT JOIN ".FILTER_GROUP_MASTER_TBL." G ON (G.id=FS.group_id)

			WHERE FS.sub_category_id='$sub_category_id' GROUP BY FS.group_id ";
		$exe =  $this->exeQuery($query);
		if (mysqli_num_rows($exe) > 0) {
			$i = 1;
			while ($row = mysqli_fetch_assoc($exe)) {
				$list = $this->editPagePublish($row);
				$layout .='<div class="filter-section">
                      <h3 class="filter-title">'.$list['filter_group_name'].'</h3>
                      <div id="'.$list['token'].'_ecom_filter" class="ecom_filter_type visible">
                          <div class="filter-options">
                             '.$this->getFilterCheckbox($filter_array,$sub_category_id,$list).'
                          </div>
                      </div>
                  </div>';
				$i++;
			}
 		}
 		return $layout;
	}

	// Dynamic Sub Category Filter Checkbox


	function getFilterCheckbox($filter_array,$sub_category_id,$group_info)
	{
		$checked_array = explode(",",@$filter_array[$group_info['token']]);
		$layout = "";
		$query = "SELECT FP.id, FP.filter_id, F.filter_value, F.token  FROM ".FILTER_VS_PRODUCT_TBL." FP
			LEFT JOIN ".FILTER_MASTER_TBL." F ON (F.id=FP.filter_id)

			WHERE FP.sub_category_id='$sub_category_id' AND FP.filter_group_id='".$group_info['group_id']."' GROUP BY FP.filter_id ";
		$exe =  $this->exeQuery($query);
		if (mysqli_num_rows($exe) > 0) {
			$i = 1;
			while ($row = mysqli_fetch_assoc($exe)) {				
				$list = $this->editPagePublish($row);
				$checked = in_array($list['filter_id'],$checked_array) ? "checked" : "";
				$layout .='<label class="filter-option">
						<input data-filter-id="'.$group_info['token'].'" data-value="'.$list['filter_id'].'" class="filter_option" type="checkbox" '.$checked.'>
						<span>'.$list['filter_value'].'</span>
					</label>';
				$i++;
			}
 		}
 		return $layout;
	}

	// shop page brand filter\

	function getShopBrandlist($page_link="",$filter_array="")
	{	
		$ids = $this->getBrandIdsFroCat($list_for,$id);

		if($ids!="")
		{
			$brand_filter = "AND id IN (".$ids.")";
		} else {
			$brand_filter = "";	
		}
		$checked_array = explode(",",@$filter_array['brand']);

		$layout = "";
		$q = "SELECT * FROM ".BRAND_TBL."  WHERE 1 AND is_draft='0' ".$brand_filter." AND delete_status='0' ORDER BY brand_name ASC";
		$exe = $this->exeQuery($q);
	 	if(mysqli_num_rows($exe)>0){
		    	$i=1;
		    	$selected = ((mysqli_num_rows($exe)==1)? "checked" : "" );
		    	while($row = mysqli_fetch_array($exe)){
  				$list  = $this->editPagePublish($row);
  				$is_empty  = $this->getDetails(PRODUCT_TBL,'id'," brand_id='".$list['id']."' AND delete_status='0' AND status='1' AND is_draft='0'  ");

	  			if(isset($_GET['brands'])) {
						$selected_ids = explode(",", $_GET['brands']);
						$checked     = (in_array($list['id'], $selected_ids))? "checked" : "" ;
					} else {
						$checked = "";
					}

		    		if($is_empty) {

		    			$checked = in_array($list['id'],$checked_array) ? "checked" : "";
		    			$layout .='<li>
						<label class="checkbox-inline checkbox-danger"><input data-filter-id="brand" data-value="'.$list['id'].'" class="filter_option styled" type="checkbox" '.$checked.'> '.$list['brand_name'].' </label>
						</li>';
		    		}
		    		$i++;
	    	}
		  }
	 	return $layout;

	}


	// Brand Filters

	function getCatgoryBrands($list_for="",$id="",$page_link="",$filter_array="")
	{	
		$ids = $this->getBrandIdsFroCat($list_for,$id);

		if($ids!="")
		{
			$brand_filter = "AND id IN (".$ids.")";
		} else {
			$brand_filter = "";	
		}
		$checked_array = explode(",",@$filter_array['brand']);

		$layout = "";
		$q = "SELECT * FROM ".BRAND_TBL."  WHERE 1 AND is_draft='0' ".$brand_filter." AND delete_status='0' ORDER BY brand_name ASC";
		$exe = $this->exeQuery($q);
	 	if(mysqli_num_rows($exe)>0){
		    	$i=1;
		    	$selected = ((mysqli_num_rows($exe)==1)? "checked" : "" );
		    	while($row = mysqli_fetch_array($exe)){
  				$list  = $this->editPagePublish($row);
  				$is_empty  = $this->getDetails(PRODUCT_TBL,'id'," brand_id='".$list['id']."' AND delete_status='0' AND status='1' AND is_draft='0'  ");

	  			if(isset($_GET['brands'])) {
						$selected_ids = explode(",", $_GET['brands']);
						$checked     = (in_array($list['id'], $selected_ids))? "checked" : "" ;
					} else {
						$checked = "";
					}

		    		if($is_empty) {

		    			$checked = in_array($list['id'],$checked_array) ? "checked" : "";
		    			$layout .='<li>
						<label class="checkbox-inline checkbox-danger"><input data-filter-id="brand" data-value="'.$list['id'].'" class="filter_option styled" type="checkbox" '.$checked.'> '.$list['brand_name'].' </label>
						</li>';
		    		}
		    		$i++;
	    	}
		  }
	 	return $layout;

	}

		// Rating Filters

	function getSubcatgoryRating()
	{	
		
		$rating_static = array('1','2','3','4','5');
		if(isset($_GET['rating'])) {
			$selected_ids = explode(",", $_GET['rating']);
			$checked     = (in_array($rating_static, $selected_ids))? "checked" : "" ;
		} else {
			$checked = "";
		}
		
		$selected_in_rating_static = array_intersect($rating_static, $selected_ids);


		$layout .='<li style="display: grid;">
			<label class="checkbox-inline checkbox-danger"><input data-filter-id="rating" data-value="'.$rating_static[4].'" class="filter_option styled" type="checkbox" '.((in_array($rating_static[4], $selected_in_rating_static))? "checked" : "").'> <img src="https://img.icons8.com/emoji/20/000000/star-emoji.png"/><img src="https://img.icons8.com/emoji/20/000000/star-emoji.png"/><img src="https://img.icons8.com/emoji/20/000000/star-emoji.png"/><img src="https://img.icons8.com/emoji/20/000000/star-emoji.png"/><img src="https://img.icons8.com/emoji/20/000000/star-emoji.png"/></label>

			<label class="checkbox-inline checkbox-danger"><input data-filter-id="rating" data-value="'.$rating_static[3].'" class="filter_option styled" type="checkbox" '.((in_array($rating_static[3], $selected_in_rating_static))? "checked" : "").'> <img src="https://img.icons8.com/emoji/20/000000/star-emoji.png"/><img src="https://img.icons8.com/emoji/20/000000/star-emoji.png"/><img src="https://img.icons8.com/emoji/20/000000/star-emoji.png"/><img src="https://img.icons8.com/emoji/20/000000/star-emoji.png"/></label>

	        <label class="checkbox-inline checkbox-danger"><input data-filter-id="rating" data-value="'.$rating_static[2].'" class="filter_option styled" type="checkbox" '.((in_array($rating_static[2], $selected_in_rating_static))? "checked" : "").'> <img src="https://img.icons8.com/emoji/20/000000/star-emoji.png"/><img src="https://img.icons8.com/emoji/20/000000/star-emoji.png"/><img src="https://img.icons8.com/emoji/20/000000/star-emoji.png"/></label>

	        <label class="checkbox-inline checkbox-danger"><input data-filter-id="rating" data-value="'.$rating_static[1].'" class="filter_option styled" type="checkbox" '.((in_array($rating_static[1], $selected_in_rating_static))? "checked" : "").'> <img src="https://img.icons8.com/emoji/20/000000/star-emoji.png"/><img src="https://img.icons8.com/emoji/20/000000/star-emoji.png"/></label>

	        <label class="checkbox-inline checkbox-danger"><input data-filter-id="rating" data-value="'.$rating_static[0].'" class="filter_option styled" type="checkbox" '.((in_array($rating_static[0], $selected_in_rating_static))? "checked" : "").'> <img src="https://img.icons8.com/emoji/20/000000/star-emoji.png"/></label>';

	 	return $layout;

	}

	// Filter Query 

	function filterQuery($filter_array,$sub_category_id)
	{	

		$sort_by 	= $filter_array['sortby'];
		$layout 	= "";

		if (isset($filter_array['brand'])) {
			$layout .= " AND P.brand_id IN (".$filter_array['brand'].") ";
		}


		if (isset($filter_array['rating'])) {
			$layout .= " AND RT.star_ratings IN(".$filter_array['rating'].") ";
		}

		if (isset($filter_array['price'])) {
			$get_price  = explode('-', $filter_array['price']);
			$from 		= $get_price[0];
			$to 		= $get_price[1];
			$layout .= " AND P.selling_price BETWEEN $from AND $to ";
		}

		$filter_ids = "";
		$j=1;

		unset($filter_array['p']);
		unset($filter_array['sortby']);


		if (count($filter_array)>0) {
			foreach ($filter_array as $key => $value) {
				if ($key!="price" && $key!="brand") {
					$and_cond = $j==1 ? "" : "AND ";
					$filter_ids .= $and_cond." (filter_ids LIKE '%".$value.",%')";
					$j++;
				}
				
			}

			$q = "SELECT product_id FROM ".FILTER_VS_PRODUCT_TBL."  WHERE sub_category_id='$sub_category_id' AND  (".$filter_ids.") GROUP BY product_id";
			$exe = $this->exeQuery($q);
			$id = "";
		 	if(@mysqli_num_rows($exe)>0){
		 			$i=1;
			    while($list = mysqli_fetch_array($exe)){
			    	$comma = $i==1 ? "" : ", ";
			    	$id .= $comma.$list['product_id'];
			    	$i++;
			    }

			    $layout .= " AND P.id IN (".$id.") ";
			}else{
				// $layout .= " AND P.id = 0 ";
			}

		}

		return $layout;

	}



		// Filter Query 

	function maincatFilterQuery($filter_array,$sub_category_id)
	{

		$sort_by 	= $filter_array['sortby'];
		$layout 	= "";

		if (isset($filter_array['brand'])) {
			$layout .= " AND brand_id IN (".$filter_array['brand'].") ";
		}


		if (isset($filter_array['rating'])) {
			$layout .= " AND RT.star_ratings IN(".$filter_array['rating'].") ";
		}

		if (isset($filter_array['price'])) {
			$get_price  = explode('-', $filter_array['price']);
			$from 		= $get_price[0];
			$to 		= $get_price[1];
			$layout .= " AND P.selling_price BETWEEN $from AND $to ";
		}

		$filter_ids = "";
		$j=1;

		unset($filter_array['p']);
		unset($filter_array['sortby']);


		if (count($filter_array)>0) {
			foreach ($filter_array as $key => $value) {
				if ($key!="price" && $key!="brand") {
					$comma = $j==1 ? "" : ", ";
					$filter_ids .= $comma.$value;
					$j++;
				}
				
			}

		}

		return $layout;

	}


		// All product Filter Query 

	function shopFilterQuery($filter_array)
	{

		$sort_by 	= $filter_array['sortby'];
		$layout 	= "";

		if (isset($filter_array['brand'])) {
			$layout .= " AND brand_id IN (".$filter_array['brand'].") ";
		}


		if (isset($filter_array['rating'])) {
			$layout .= " AND RT.star_ratings IN(".$filter_array['rating'].") ";
		}

		if (isset($filter_array['price'])) {
			$get_price  = explode('-', $filter_array['price']);
			$from 		= $get_price[0];
			$to 		= $get_price[1];
			$layout .= " AND P.selling_price BETWEEN $from AND $to ";
		}

		$filter_ids = "";
		$j=1;

		unset($filter_array['p']);
		unset($filter_array['sortby']);


		if (count($filter_array)>0) {
			foreach ($filter_array as $key => $value) {
				if ($key!="price" && $key!="brand") {
					$comma = $j==1 ? "" : ", ";
					$filter_ids .= $comma.$value;
					$j++;
				}
				
			}

		}

		return $layout;

	}


	//Notification Curl

	function curlMaster($data)
	{
		// Get cURL resource
		$curl = curl_init();
		// Set some options - we are passing in a useragent too here
		curl_setopt_array($curl, array(
		    CURLOPT_RETURNTRANSFER => 1,
		    CURLOPT_URL => COREPATH.'resource/curl.php',
		    CURLOPT_USERAGENT => 'Alumbook cURL request',
		    CURLOPT_POST => 1,
		    CURLOPT_CONNECTTIMEOUT => 0,
		    CURLOPT_TIMEOUT => 1,
		    CURLOPT_POSTFIELDS => $data
		));
		// Send the request & save response to $resp
		$resp = curl_exec($curl);
		//return $resp;
		// Close request to clear up some resources
		curl_close($curl);
	}

	// Send Push notification GCM

	function sendPushNotification($registration_id,$message)
	{
		$fields = array(
			'registration_ids' 	=> $registration_id,
			'data'				=> $message
		);
		 
		$headers = array
		(
			'Authorization: key='.API_ACCESS_KEY,
			'Content-Type: application/json',
		);
		$ch = curl_init();
		curl_setopt( $ch,CURLOPT_URL, 'https://android.googleapis.com/gcm/send' );
		curl_setopt( $ch,CURLOPT_POST, true );
		curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
		curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
		curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
		$result = curl_exec($ch );
		curl_close( $ch );
		return $result;
	}

	// Send Push notification FCM

	function sendPushNotificationFcm($registration_id,$message)
	{
		$url = 'https://fcm.googleapis.com/fcm/send';
		$fields = array(
			'registration_ids'	=> $registration_id,
			'data'			=> $message
		);
		 
		$headers = array
		(
			'Authorization: key='.API_SERVER_KEY,
			'Content-Type: application/json',
		);
		$ch = curl_init();
		curl_setopt( $ch,CURLOPT_URL, $url );
		curl_setopt( $ch,CURLOPT_POST, true );
		curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
		curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
		curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
		$result = curl_exec($ch );
		curl_close( $ch );
		return $result;
	}


	/*-----------Dont'delete---------*/

}?>