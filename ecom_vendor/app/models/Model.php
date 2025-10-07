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

  	// Email for admin replace an rejected orders

  	function OrderStatusupdateTemplate($vendor_order_id="")
	{

		$layout ="";
		$v_order_info = $this->getDetails(VENDOR_ORDER_TBL, "*","id='".$vendor_order_id."'");
        $v_info       = $this->getDetails(VENDOR_TBL, "*","id='".$v_order_info['vendor_id']."'");
		$info 		  = $this->getDetails(ORDER_TBL,"*"," id='".$v_order_info['order_id']."'");
		$address 	  = $this->getDetails(CUSTOMER_ADDRESS_TBL,"*"," id='".$info['order_address']."' ");
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

		$inr_format = $this->inrFormatFields($info);
		$inprocess  = $this->check_query(ORDER_ITEM_TBL,"order_status","order_status='0' AND order_id='".$info['id']."' ");
		$shipped    = $this->check_query(ORDER_ITEM_TBL,"order_status","order_status='1' AND order_id='".$info['id']."' ");
		$delivered  = $this->check_query(ORDER_ITEM_TBL,"order_status","order_status='2' AND order_id='".$info['id']."' ");
		$returned   = $this->check_query(ORDER_ITEM_TBL,"order_status","order_status='3' AND order_id='".$info['id']."' ");

		$item_total 		   = $this->getDetails(ORDER_ITEM_TBL,"SUM(sub_total + total_tax ) as total","order_id='".$info['id']."' ");
		$rejected_item_total   = $this->getDetails(ORDER_ITEM_TBL,"SUM(sub_total + total_tax ) as total","order_id='".$info['id']."' AND vendor_response='1' AND vendor_accept_status='0'  ");
		$coupon_total 		   = $this->getDetails(ORDER_ITEM_TBL,"SUM(COALESCE(coupon_value, 0)) as total","order_id='".$info['id']."' ");
		$rejected_coupon_total = $this->getDetails(ORDER_ITEM_TBL,"SUM(COALESCE(coupon_value, 0)) as total","order_id='".$info['id']."' AND vendor_response='1' AND vendor_accept_status='0'  ");

		$order_item_tot 	= $this->getDetails(ORDER_ITEM_TBL,"count(id) as count"," order_id='".$info['id']."' AND vendor_accept_status='1' ");

		if(!$order_item_tot['count']) {
			$order_total  = $item_total['total'];
			$coupon_total = $coupon_total['total'];
		} else {
			$order_total  = $item_total['total'] - $rejected_item_total['total'] ;
			$coupon_total = $coupon_total['total'] - $rejected_coupon_total['total'] ;
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
				                                        <th style='border-bottom:2px solid #ccc'>Status</th>
				                                        <th style='border-bottom:2px solid #ccc'>Cost</th>
				                                        <th style='border-bottom:2px solid #ccc'>Qty</th>
				                                        <th style='border-bottom:2px solid #ccc' align='right'>Total </th>
				                                    </thead>
				                                    <tbody>
				                                        ".$this->emailGetOrderDetails($info['id'])."
				                                        <tr align='right' style='padding: 25px 0px;'>
				                                            <td></td>
				                                            <td></td>
				                                            <td></td>
				                                            <td style='border-bottom:1px solid #ccc' align='right'>Sub total</td>
				                                            <td style='border-bottom:1px solid #ccc;white-space: nowrap;'>Rs. ".number_format($info['total_amount'],2)."</td>
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
				                                            <td style='border-bottom:1px solid #ccc;line-height: normal;white-space: nowrap;' align='right'><strong>Discount </strong>".(($info['coupon_code']!="")?"Coupon Code :   ".$info['coupon_code']." Applied" : "")."</td>
				                                            <td style='border-bottom:1px solid #ccc'> &#8722; Rs. ".number_format($info['coupon_value'])."</td>
				                                        </tr>

				                                        <tr align='right' style='padding: 25px 0px;'>
				                                            <td></td>
				                                            <td></td>
				                                            <td></td>
				                                            <td style='border-bottom:2px solid #ccc'><strong>Total</strong></td>
				                                            <td style='border-bottom:2px solid #ccc;white-space: nowrap;'>₹ ".number_format($order_total + $info['shipping_cost'] - $coupon_total,2) ." </strong> </td>
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
         $query  ="SELECT O.id,O.user_id,O.variant_id,O.order_id,O.cart_id,O.product_id,O.coupon_value,O.vendor_id,O.variant_id,O.order_id,O.category_id,O.sub_category_id,O.price,O.tax_amt,O.tax_type,O.final_price,O.qty,O.sub_total,O.total_tax,O.sgst,O.order_status,O.cgst,O.igst,O.sgst_amt,O.cgst_amt,O.igst_amt,O.total_amount,O.vendor_response,O.vendor_accept_status,O.response_notes,O.return_status,O.return_reason,O.return_comment,P.product_uid,P.product_name,P.page_url,V.variant_name,O.vendor_id,O.vendor_invoice_number,VD.state_id,VD.state_name,VD.company,OD.order_address,RS.response_status as response_status_msg,RR.return_reason  as return_reason_msg,PU.product_unit,
         	(SELECT file_name FROM ".MEDIA_TBL." WHERE item_id=O.product_id AND item_type='product' AND delete_status=0 ORDER BY id ASC LIMIT 1) as  image
            FROM ".ORDER_ITEM_TBL." O LEFT JOIN ".PRODUCT_TBL." P ON (P.id=O.product_id) 
                                      LEFT JOIN ".VENDOR_TBL." VD ON (VD.id=O.vendor_id) 
                                      LEFT JOIN ".ORDER_TBL." OD ON (OD.id=O.order_id) 
                                      LEFT JOIN ".PRODUCT_VARIANTS." V ON (O.variant_id=V.id) 
                                      LEFT JOIN ".ORDER_RESPONSE_STATUS_TBL." RS ON (RS.id=O.response_status) 
                                      LEFT JOIN ".RETURN_REASON_TBL." RR ON (RR.id=O.return_reason) 
                                      LEFT JOIN ".PRODUCT_UNIT_TBL." PU ON (PU.id=P.product_unit)  
            WHERE O.order_id=$order_id   ORDER BY  O.vendor_invoice_number ASC";

        $exe = $this->exeQuery($query);
        $count = mysqli_num_rows($exe);
        if ($count > 0) {
            $i = 1;
            while ($rows = mysqli_fetch_assoc($exe)) {
				$list   = $this->editPagePublish($rows);

                	$pic = (($list['image']!="") ? "<img class='tr_all_long_hover' src='".SRCIMG.$list['image']."' alt='' style='width:150px;'>" : "<img class='tr_all_long_hover' src='".ASSETS_PATH."no_img.jpg' alt='' style='width:150px;'>");


                    $info   = $this->getDetails(VENDOR_TBL,"company","id='".$list['vendor_id']."' ");
                    $name   = $list['variant_id']==0 ? $list['product_name'] : $list['product_name']." - ".$list['variant_name'] ;

                    $billing_address = $this->getDetails(CUSTOMER_ADDRESS_TBL,"state_id","id=".$list['order_address']." ");

                    if($billing_address['state_id']==$list['state_id']) {
                        $tax_info = "<p>SGST : ".$list['sgst']."% (₹  ".$this->inrFormat($list['sgst_amt']).")</p>
                                        <p>CGST : ".$list['sgst']."% (₹  ".$this->inrFormat($list['cgst_amt']).")</p>";
                    } else {
                        $tax_info = "<p>IGST : ".$list['igst']."% (₹  ".$this->inrFormat($list['igst_amt']).")</p>";
                    }

                    if($list['tax_type']=="inclusive") 
                    {
                        $taxMsg = "<p>( Inclusive of all taxes * )</p>";
                    } else {
                        $taxMsg = "<p>( Exclusive of all taxes * )</p>";
                    }

                    if($list['return_comment']!="") {
                        $return_comment = "<div>Comment : ".$list['return_comment']."</div>";
                    } else {
                        $return_comment = "";
                    }

                    $status_btn_title = (($list['order_status']==0) ? "<span class='text-warning'>Inprocess</span>" :  (($list['order_status']==1) ? "<span class='text-warning'>Shipped</span>" :  (($list['order_status']==2) ? "<span class='text-success'>Paid</span>" :  "<span class='text-danger'>Returned</span><div>Reason : ".$list['return_reason_msg']." ".$return_comment ." </div>") ) );

                    if($list['vendor_response']==1 && $list['vendor_accept_status']==0)
                    {
                        $status_btn_title = "<span style='color:red;' >Rejected</span>";
                        $response_notes   = "<div style='color:red;'>
                                            ( ".$list['response_status_msg']." )
                                            <div>" ;
                        $rejected_price   = "color : red;";                                            
                    } elseif($list['vendor_response']== 0 )  {
                        $status_btn_title = "<span class='text-warning'>Not Seen</span>";
                        $response_notes   = "" ;
                        $rejected_price   = "";                        
                    } else {
                        $response_notes = "";
                        $rejected_price   = "";                        
                    }


                    $product_unit = (($list['product_unit']!="")? "( ".$list['product_unit']." )" : "" );


                $layout .="
                <tr style='padding: 25px 0px;'>
                    <td>
                    	<a href='".BASEPATH."product/details/".$list['page_url']."' target='_blank' class='color_dark d_inline_b m_bottom_5' style='line-height:inherit;white-space: nowrap;'>".$name."</a>
                        ".$taxMsg."
                        ".$tax_info."
                        <p>Sold by: ".$info['company']."</p>
                    </td>	
                    <td style='white-space: nowrap;'>
                        ".$status_btn_title."
                        ".$response_notes."
                    </td>
                    <td   style='white-space: nowrap; $rejected_price'><p class='f_size_large color_dark'>₹ ".$this->inrFormat($list['price'])."</p></td>
                    <td style='$rejected_price' >".$list['qty']." ".$product_unit."</td>
                    <td  align='right' style='white-space: nowrap; $rejected_price'><p class='color_dark f_size_large'>₹ ".$this->inrFormat($list['sub_total']+$list['total_tax'])."</p></td>

                </tr>	
                ";
            $i++;
            }
        }
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
		$query = "SELECT U.* FROM ".VENDOR_TBL." U WHERE U.id ='".$_SESSION["ecom_vendor_id"]."' ";
		$exe = $this->exeQuery($query);;
		$list = mysqli_fetch_assoc($exe);
		return $list;
	}

	// Get order notification

	function getOrderNotification()
	{
		$result = array();
		$query  = "SELECT * FROM ".VENDOR_ORDER_TBL." WHERE (vendor_response='0' OR return_status=1) AND vendor_id='".$_SESSION['ecom_vendor_id']."' ";
		$exe    = $this->exeQuery($query);;
		$list   = mysqli_fetch_assoc($exe);

		$result['count']  = mysqli_num_rows($exe); 
		$result['layout'] = $this->orderNotificationLayout();
		return $result;
	}

	// Notification list

	function orderNotificationLayout()
	{
		$layout    = "";
		$vendor_id = $_SESSION['ecom_vendor_id'];

		$query  = "SELECT VO.id,VO.vendor_id,VO.user_id,VO.order_id,VO.sub_total,VO.total_tax,VO.vendor_payment_total,VO.vendor_commission_total,VO.vendor_shipping_total,VO.created_at,VO.return_status,C.name,C.mobile,C.email,VO.return_status,
		(SELECT COUNT(id) FROM ".VENDOR_ORDER_ITEM_TBL." OI WHERE order_id=VO.id AND vendor_id='".$vendor_id."' ) as items, 
				(SELECT SUM(sub_total) + SUM(total_tax)  FROM ".VENDOR_ORDER_ITEM_TBL." OI WHERE order_id=VO.id AND return_status='1' AND vendor_id='".$vendor_id."') as return_total
		 FROM ".VENDOR_ORDER_TBL." VO LEFT JOIN ".CUSTOMER_TBL." C ON(VO.user_id=C.id) WHERE (vendor_response='0' OR return_status=1)  AND vendor_id='".$_SESSION['ecom_vendor_id']."' ORDER BY VO.id DESC ";

		$exe    = $this->exeQuery($query);
		if(mysqli_num_rows($exe) > 0)
		{
			while ($list = mysqli_fetch_array($exe)) {

				$notification_icon = (($list['return_status']==1)? "bg-danger-dim ni ni-curve-up-left" : "bg-success-dim ni ni-curve-down-right" );

				$total = (($list['return_status']==1)? $this->inrFormat($list['return_total']) : $this->inrFormat($list['sub_total'] + $list['total_tax']  ) );
				
				$layout .="
				<div class='nk-notification-item dropdown-inner notification_td' onclick='notificationTd(".$list['id'].")'>
                    <div class='nk-notification-icon'>
                        <em class='icon icon-circle $notification_icon'></em>
                    </div>
                    <div class='nk-notification-content'>
                        <div class='nk-notification-text'>".ucfirst($list['name'])."<span> ₹ ".$total."</span></div>
             	        <div class='nk-notification-time'>".$this->to_time_ago(time() - strtotime($list['created_at']))."</div>
                    </div>
                </div>";
			}
		}
		return $layout;
	}

	// Check Permission

	function checkPermissionPage($token)
	{	
		if (@$_SESSION['is_super_admin']==1) {
			return 1;
		} else {
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

	// INR Formate

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

	function vendorInvoicePdf($token,$products)
	{

  	$order_item_info   = $this->getDetails(ORDER_ITEM_TBL,"*"," order_id='$token' AND vendor_id='".$_SESSION['ecom_vendor_id']."' ");
    $order_info 	   = $this->getDetails(ORDER_TBL,"*"," id='".$order_item_info['order_id']."' ");
    $vendor_info 	   = $this->getDetails(VENDOR_TBL,"*"," id='".$order_item_info['vendor_id']."' ");
    $order_address 	   = $order_info['order_address'];
    $ship_address  	   = $this->getDetails(CUSTOMER_ADDRESS_TBL,"*"," id='$order_address' ");
    $cus_info          = $this->getDetails(CUSTOMER_TBL,"*","id='".$order_item_info['user_id']."' ");
    $gstname           = ($ship_address['gst_name']!="")? 'GST name : '.$ship_address['gst_name']."" : "";
	$gstno             = ($ship_address['gstin_number']!="")? 'GSTIN : '.$ship_address['gstin_number']."" : "";
	$file_name         = $order_item_info['vendor_invoice_number'];


	$layout  = '';  
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
					<td align="right">Zupply Order Id: '.$order_info['order_uid'].'</td>
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
		//$content .= fetch_data();  
		$result = array();
		$result['layout'] = $layout;
		$result['file_name'] = ".$file_name.";
		return $result;
	}
	// Vendor payouts invoice

	function vendorPayoutInvoicePdf($vendor_id,$payouts,$payout_id)
	{

    $vendor_info 	= $this->getDetails(VENDOR_TBL,"*"," id='".$vendor_id."' ");
    $payout_info 	= $this->getDetails(VENDOR_PAYOUT_INVOICE_TBL,"*"," id='".$payout_id."' ");
    $company_info 	= $this->getDetails(COMPANNY_INFO,"*","id='1'");

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
						<td><h3>Vendor Payout Invoice</h3>
						Date: '.date("d-M-Y",strtotime($payout_info['created_at'])).' </td>
						<td align="right">Invoice Id: '.$payout_info['payout_invoice_id'].'</td>
					</tr>
				</tbody>
			</table>

			<table style="padding:20px 20px 20px 25px;">
				<tbody>
					<tr>
						<td><strong>'.$company_info['company_name'].' </strong><br/>
						'.$company_info['email_one'].', <br/>
						'.$company_info['address_one'].'.
						</td>
						<td style="float:right;"><strong>Vendor Info: '.$vendor_info['company'].'</strong><br/>
						'.$vendor_info['address'].',<br/>
						'.$vendor_info['city'].', ' .$vendor_info['state_name']  .' - '.  $vendor_info['pincode'].'.<br/>
						Ph : '.$vendor_info['mobile'].'.<br/>
						GSTIN: '.$vendor_info['gst_no'].'..
						</td>
					</tr>
				</tbody>
			</table>    

			<table style="padding:20px 35px; border-top:1px solid #222;" cellpadding="10" cellspacing="0" border="0">
			<thead style="background-color: #f3f0ec;">
				<tr style="font-weight:bold;">
					<th style="text-align: center;">Order Invoice No</th>
					<th style="text-align: left;">Paid Date</th>
					<th style="text-align: center;">Customer</th>
					<th style="text-align: center;">Amount</th>
					<th style="text-align: center;">Total Charge</th>
					<th style="text-align: center;">Net Payable</th>
					<th style="text-align: right;">Status</th>
				</tr>
			</thead>
				<tbody>
					'.$payouts.'
				</tbody>
			</table>
			';  
			//$content .= fetch_data();  
			$result = array();
			$result['layout'] = $layout;
			$result['file_name'] = ".$file_name.";
			return $result;
	}


}


?>