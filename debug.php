<?php

	
	$gmt_date=gmdate("Y-m-d");
	$gmt_time=gmdate("H:i:s");

	if(isset($_REQUEST['app_type']) && isset($_REQUEST['device_info']) && isset($_REQUEST['screen_name']) && isset($_REQUEST['error_details']))
	{	
		$app_type=$db->real_escape_string($_REQUEST['app_type']);
		$device_info=$db->real_escape_string($_REQUEST['device_info']);
		$screen_name=$db->real_escape_string($_REQUEST['screen_name']);
		$error_details=$db->real_escape_string($_REQUEST['error_details']);
		$extra_data=$db->real_escape_string($_REQUEST['extra_data']);
		
		$query="INSERT INTO debug_app_data(app_type,device_info,screen_name,error_details,extra_data,report_date,report_time) VALUES('".$app_type."','".$device_info."','".$screen_name."','".$error_details."','".$extra_data."','".$gmt_date."','".$gmt_time."')";
		$result=$db->query($query);

		//$filenameqdata=date('Y-m-d-H-i-s')."-error.txt";
		//$filenameqdata1="errors/".$filenameqdata;
		//$fp = fopen($filenameqdata1, 'wb');
		//fwrite($fp,json_encode($_REQUEST));  
		//fclose($fp);

		//send_email("mayur@smart-webtech.com",json_encode($_REQUEST),"Error in Master Trainer App",1,array($filenameqdata));
		$status=1;
		$msg="Login Successfully";
	}
	else
	{
		$status=0;
		$msg="Please check parameters"; 
	}


?>