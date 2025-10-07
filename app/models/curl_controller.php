<?php
require_once '../app/models/Model.php';
require_once 'classes/PHPMailerAutoload.php';

/**
* CURL Classes
*/

class Curlcontroller extends Model
{


	/*--------------------------------------------- 
				Help Notification
	----------------------------------------------*/

	// New Help Notification

	function sendHelpNotification($data)
	{

		$order_id	= $data['order_id'];
		$employee_id = $data['employee_id'];	

		//return "test";

		$msg = array(
			'title'		=> $name." Orders Details",
			'subtitle'	=> $order_id,
			'item_type'	=> 'help',
			'item_id'	=> $employee_id
		);
		$mdata = array(
				'mtitle'	=> $name." posted an help request",
				'mdesc'		=> $content,
			);
		$click_data = array(
				'item_type'	=> 'help',
				'item_id'	=> $item_id
			);
		$info 	= $this->getDetails(EMPLOYEE_TBL,"android_token,ios_token"," id='".$employee_id."'");
		$this->sendPushNotificationFcm($device_tokens,$msg);
		/*if($info['android_token']!=""){
			
		}*/

		if($info['ios_token']!=""){
			$this->sendiOSBulkPushNotification($mdata,$click_data,$type="help",$user_id);
		}

	}






}


?>