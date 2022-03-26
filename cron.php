<?php 
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
date_default_timezone_set('Asia/Kolkata');

include "conn.php";
include "function.php";


//* * * * * lynx -dump http://173.255.254.63/apps/trainer_app/cron.php
//	$result=$db->query("select * tbl_trainer from where pass_key='$token' AND email='$email' LIMIT 1");

?>