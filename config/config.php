<?php	
	//require_once './global/global-config.php'; 
	
	//--------------------- WEBSITE PATH DEFINITIONS ---------------------//
	
	// Website Base Path
	define('BASEPATH', HOST_NAME);
	// Define image folder path
	define('IMGPATH', HOST_NAME.'lib/images/');
	// Define css folder path
	define('CSSPATH', HOST_NAME.'lib/css/');
	// Define js folder path
	define('JSPATH', HOST_NAME.'lib/js/');
	// Define js folder path
	define('FONTPATH', HOST_NAME.'lib/fonts/');
	// Define Plugin folder path
	define('PLUGINS', HOST_NAME.'lib/plugins/');
	
		
	//--------------------- DATABASE CONNECTION ---------------------//

	// Connect Mysql DB
	$connect = mysqli_connect(DB_SERVER , DB_USER, DB_PASSWORD, DB_DATABASE);
	
?>