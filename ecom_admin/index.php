<?php
session_start();
ini_set('max_execution_time', 500);
require_once '../global/global-config.php';
require_once 'config/config.php';
require_once 'app/init.php';
$app = new App();
?>