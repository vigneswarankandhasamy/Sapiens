<?php
	require_once 'environments.php';
	require_once 'database-tables.php';
		
	//--------------------- WEBSITE SETTINGS --------------------//

	
	// Company Path
	define('COMPANY_NAME', 'Zupply');
	// Domain Name
	define('DOMAIN', '');
	// Admin Folder
	define('ADMIN_PATH', HOST_NAME.'ecom_admin/');
	// Vendor Folder
	define('VENDOR_PATH', HOST_NAME.'ecom_vendor/');
	// Hire Folder
	define('HIRE_PATH', HOST_NAME.'ecom_hire/');
	// API Folder
	define('API_PATH', HOST_NAME.'api/');
	// Assets Folder
	define('ASSETS_PATH', HOST_NAME.'assets/'); 
	// Location Module
	define('LOCATION_MODULE', 'enabled');


	//--------------------- EMAIL SETTINGS --------------------//

	// Admin Mail Address
	define('ADMIN_EMAIL', 'admin@zupply.com');
	// No Reply Mail Address
	define('NO_REPLY', 'no-reply@zupply.com');
	// Reply to Mail Address
	define('REPLY_TO', 'info@zupply.com');
	// Email Footer Address
	define('EMAIL_FOOTER', "".COMPANY_NAME."',Email:'".ADMIN_EMAIL." ");

	// Payment Gateway Account ID
	define('RAZORPAY_ACCOUNT_ID', 'HNCzxCFvO40S9z');
	// Merchant ID Capture
	define('IS_MERCHANT_VENPEP', 'enabled');
	
	
	//--------------------- DEVICE APP VERSION --------------------//

	// Android version
	define('ANDROID_CURRENT_VERSION', '1.0.0');
	// Ios version
	define('IOS_CURRENT_VERSION', '1.0');
	// Android Previous version
	define('ANDROID_PREVIOUS_VERSION', '0.0');
	// Ios Previous version
	define('IOS_PREVIOUS_VERSION', '0.0');
	// Delete Account Mobile App
	define('DELETE_ACCOUNT', 'yes');


	//--------------------- DEVICE APP Server and Accept Key --------------------//

	// Android version
	define('API_SERVER_KEY', '');


	//--------------------- APP LOADING COUNTS PER PAGE --------------------//

	// Product Count
	define('PRODUCT_COUNT', '8');
	// Blog Count
	define('BLOG_COUNT', '10');
	// Wishlist Count
	define('WISHLIST_COUNT', '10');
	// Reviewlist Count
	define('REVIEWLIST_COUNT', '5');
	// Agent Order Count
	define('DELIVERYORDER_COUNT', '5');	

	//--------------------- Image PATH --------------------//

	// Define Image path
	define('SRCIMG', IMAGEKIT_SRC); 
	// Define Image path
	define('HIRE_UPLOADS', IMAGEKIT_HIRE);
	// Define Uploads path
	define('UPLOADS', IMAGEKIT_SRC);

	require_once 'admin-permission.php';
    require_once 'site-contents.php';
	require_once 'meta-contents.php';
	require_once 'mobile-config.php';


?>