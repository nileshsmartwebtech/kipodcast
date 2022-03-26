<?php 
$db=mysqli_connect($_ENV['DB_HOST'],$_ENV['DB_USER'],$_ENV['DB_PASS'],$_ENV['DB_NAME']);
$db->set_charset("utf8");
$db->query( "SET NAMES utf8" );
$db->query( "SET CHARACTER SET utf8" );
$base_url=$_ENV['SITE_URL'];
?>