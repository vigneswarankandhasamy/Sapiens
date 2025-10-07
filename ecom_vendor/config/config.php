<?php	
	//require_once './global/global-config.php'; 
	
	//--------------------- WEBSITE PATH DEFINITIONS ---------------------//
	
	// Website Base Path
	define('BASEPATH', HOST_NAME);
	// Website Base Path
	define('COREPATH', VENDOR_PATH);
	// Define image folder path
	define('IMGPATH', VENDOR_PATH.'lib/images/');
	// Define css folder path
	define('CSSPATH', VENDOR_PATH.'lib/css/');
	// Define js folder path
	define('JSPATH', VENDOR_PATH.'lib/js/');
	// Define js folder path
	define('FONTPATH', VENDOR_PATH.'lib/fonts/');
	// Define Plugin folder path
	define('PLUGINS', VENDOR_PATH.'lib/plugins/');
		
	
	//--------------------- DATABASE CONNECTION ---------------------//

	// Connect Mysql DB
	$connect = mysqli_connect(DB_SERVER , DB_USER, DB_PASSWORD, DB_DATABASE);
	
?>