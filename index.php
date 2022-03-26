<?php 
//error_reporting(E_ALL);
error_reporting(E_ERROR | E_PARSE);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
//ini_set('upload_max_filesize', 100M);
//ini_set('post_max_size', 100M);
ini_set('default_charset', 'UTF-8');
date_default_timezone_set('Asia/Kolkata');
try{

include __DIR__."/../wp-config.php";
include __DIR__."/vendor/autoload.php";
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();
include __DIR__."/conn.php";
include __DIR__."/function.php";
$db->set_charset("utf8");

if(isset($_REQUEST['action']))
    $action=$_REQUEST['action'];
else
    $action="-";

$status=0;
$msg="";
$gbl_msg_invalid_token="Your session has been expired. Please try again after login";
$gbl_msg_invalid_args="Invalid Arguments.";
$output_type=1;
$base_url=$_ENV['SITE_URL'];
$data=array();
$settings=array();
$app_token="123456";

if(isset($settings['app_token']))
{
    $app_token=$settings['app_token'];
}

switch ($action) {
    case "Debug":
        include "debug.php";
        break;
    case "GetCategory":
        include "get_category_list.php";
        break;
    case "GetPostByCategory":
        include "get_post_list.php";
        break;
    case "GetSinglePost":
        include "get_post_detail.php";
        break;
    case "test":
        include "test.php";
        break;
    default:
        $msg="Please provide valid action parameter.";
}
if($output_type==1){
    header('Content-Type: application/json');
    $arr['status']=$status;
    $arr['msg']=$msg;
    $arr['data']=$data;
    //echo json_encode($arr,JSON_UNESCAPED_SLASHES);
    echo json_encode($arr);
}
}catch(Exception $ex)
{
	echo $ex->getMessage();
}