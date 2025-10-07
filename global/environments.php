<?php
	$host = $_SERVER['HTTP_HOST'];
	
	//--------------------- SELECT ENVIRONMENT ---------------------//

	switch ($host) {

		case 'localhost':
			// Mail Settings
			require_once 'mail-local.php';

			define("DB_SERVER", "localhost");
			define("DB_USER", "root");
			define("DB_PASSWORD", "");
			define("DB_DATABASE", "zupply_ecom");

			// Host Name
	   		define('HOST_NAME', 'http://localhost/venpep/zupply_ecom/');
	   		// Site Mode
	   		define('SITE_MODE', 'development'); 
	   		// Send Email from the server
	   		define('SEND_EMAIL', 'disabled');
	   		// Venpep Merchant id - Dev
	   		define('PAYMENT_GATEWAY_CLIENT_ID', '');
	   		define('PAYMENT_GATEWAY_CLIENT_SECRECT', '');	

	   		/*-------------------------------------------------------------
	   			Plugins Enabled 			{ enabled | disabled }
			-------------------------------------------------------------*/
	   		// Migration Module
	   		define('MIGRATION_MODULE', 'disabled');	

	   		/*-------------------------------------------------------------
	   			IMAGEKIT PATH	
			-------------------------------------------------------------*/

	   		// SRC Image
	   		define('IMAGEKIT_SRC', HOST_NAME.'ecom_admin/resource/uploads/');
	   		// Hire Image
	   		define('IMAGEKIT_HIRE', HOST_NAME.'ecom_hire/resource/uploads/hire_uploads/');
	   		
		break;


		default:
		break;
	}

?>
