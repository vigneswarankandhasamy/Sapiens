<?php 
session_start();
require_once '../app/core/admin_ajaxcontroller.php';
//require_once '../app/core/classes/html2pdf.class.php';
$route = new Ajaxcontroller();

$page = @$_REQUEST["page"];
$data = @$_REQUEST["element"];
$term = @$_REQUEST["term"];
$auto_type = @$_REQUEST["type"];
$post = @$_POST["result"];	


switch($page){

	// User Login 
	case 'userLogin':
		//print_r($_POST);
		echo $route -> userLogin($_POST);
	break;

	case 'resetpassword':
	    echo $route -> resetPassword($_POST);
	break;

	case 'addMigration':
		echo $route->addMigration($_POST);
	break;


}
?>