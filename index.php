<?php

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// //error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
// error_reporting(E_ERROR | E_PARSE);

session_start();

require_once 'global/global-config.php'; 
require_once 'config/config.php';
require_once 'app/init.php';
$app = new App();

// header("Location: ".BASEPATH."maintenance");

?>