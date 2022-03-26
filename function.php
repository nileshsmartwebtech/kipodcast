<?php 
//define("GOOGLE_API_KEY", "AIzaSyC69aW6Gz7zzPp3lH1RaNzgy2IvSRq74hc");
define("GOOGLE_API_KEY", "AIzaSyANbPrUt4-CljdYk9GTh7g0Yh0_jGvKg_M");
define("DEVELOPER_MODE", "true");
	include "smtp/class.smtp.php";
	include "smtp/class.phpmailer.php";
	include "smtp/email.config.php";

function get_post_detail($pid)
{
	$single=[];
 	$single['pid']=$pid;
 	$single['image']=get_thumbnail_url($pid, 'medium');
 	$single['audio']=get_field('audio_url',$pid);
 	$single['thumb']=get_thumbnail_url($pid, 'thumbnail');
 	$single['mobart']=get_thumbnail_url($pid, 'medium');
 	$single['title']=get_the_title($pid);
 	$single['cat_slug']="";

 	$podid=$pid;
	$cid=get_field('channel',$podid);
		if 	(has_category('',$cid)):
			$category = get_the_category($cid);
			//$single['category']=$category;
			if($category[0]->cat_name!=='דעות'):
				$single['cat_name']=$category[0]->cat_name;
				$single['cat_slug']=$category[0]->slug;
			else: 
				$single['cat_name']=$category[1]->cat_name;
				$single['cat_slug']=$category[1]->slug;
			endif;
		else:
			$single['cat_name']='כללי';
		endif;

	$single['podcast_name']=get_field('podcast_name',$pid);
	$post_date = get_the_date( 'j בF, Y' ,$pid);
	$single['post_date']=$post_date;
	$single['post_time']="";
	if(strlen(get_field('time',$pid))>0):
		 $single['post_time']=get_field('time',$pid);
	endif;

	$creator=get_field('channel',$pid);

	if(strlen(get_field('podcast_name',$pid))>0):
			$single['podcast_name']=get_field('podcast_name',$pid);
	elseif (strlen($creator)>0):
			$single['podcast_name']=get_the_title($creator,$pid);
	endif;

	$single['desc_short']=get_the_excerpt($pid);

	wp_reset_postdata();
	return $single;
}
function get_category_list()
{
	global $db;
	$list=[];
	$ignore_list=['1'];
	$sql="SELECT * FROM `wp_terms` t WHERE t.term_id IN (SELECT tx.term_id FROM `wp_term_taxonomy` tx WHERE tx.`taxonomy` LIKE 'category' )";
	$result=$db->query($sql);
	if($result)
	{
		if($result->num_rows>0)
		{
			while($row=$result->fetch_assoc())
			{
				if(!in_array($row['term_id'],$ignore_list))
					$list[]=$row;
			}
		}
	}
	
	return $list;
}
function send_push_notification($title,$device_token,$data1)
{

	/*$check=check_is_delete_udid($device_token);
	if($check==0)
	{
		return "User is deleted";
	}*/

	//'notification' => array('title' => $title, 'body' => $data['description'],'sound'=>'default'),
	$path_to_firebase_cm = 'https://fcm.googleapis.com/fcm/send';
       /* $fields = array(
            'to' => $device_token,
            'data' => $data1,
            'notification' => array('title' => $title, 'body' => $data1['description'],'sound'=>'default','click_action'=>'msg')
        );*/


      //  $data1['description'] = str_replace("\u", "u", $data1['description']);
        //json_decode("'".$title."'")
        $data1['description'] = json_decode('"' . $data1['description'] . '"');
         $fields = array(
            'to' => $device_token,
            'data' => $data1,
            'notification' => array('title' => json_decode('"'.$title.'"'), 'body' => $data1['description'],'sound'=>'default')
        );

        $headers = array(
            'Authorization:key=AAAAjjhCOvE:APA91bG4QFVgixJfw9r20FjweZybwd1R5QJ6GYob7qi_-JkmMdKbKVyWKWigCpbrnzP7bQHbSnUGMe5CNPnwn2WSUInmbYd8oEdV38If1NK8bkmlFE55bjYI_HghNG6X2Njm4ujLJBDg',
            'Content-Type:application/json'
        );         
        $ch = curl_init();


        curl_setopt($ch, CURLOPT_URL, $path_to_firebase_cm); 
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4 ); 
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));

        $result1 = curl_exec($ch); 
        
        curl_close($ch);// die;
        
        return $result1;
     
}// SEND PUSH NOTIFICATION









    
function post_request($url,$post=array())
{
	$ch = curl_init();        
		// Set the url, number of POST vars, POST data        
		curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);        
	//	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);       
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS,http_build_query($post));
        // Disabling SSL Certificate support temporarly		
		curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);        
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);        
		
        
		// Execute post        
		$result = curl_exec($ch);
		
		
		return $result;
		
		// Close connection        
		curl_close($ch);

}//post_request




	function sendAndroidPushNotification($registatoin_ids, $message) 	
	{        
		//$url = 'https://android.googleapis.com/gcm/send';
		$url = 'https://fcm.googleapis.com/fcm/send';

		$fields = array			
			(				
				'registration_ids' => $registatoin_ids,				
				'data' => $message,
				'priority' => "high"
			);
			
        $headers = array			
			(				
				'Authorization: key=' . GOOGLE_API_KEY,				
				'Content-Type: application/json'			
			);
			
        // Open connection        
		$ch = curl_init();        
		// Set the url, number of POST vars, POST data        
		curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);        
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);       
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // Disabling SSL Certificate support temporarly		
		curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);        
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);        
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        
		// Execute post        
		$result = curl_exec($ch);
		
		//echo "result ---->".$result;		
		$result1 = json_decode($result);
		
		// Close connection        
		curl_close($ch);
		
		return $result;
	}

	function sendApplePushNotification($deviceToken, $message,$data=array())	
	{
		$ctx = stream_context_create();
		
		if(DEVELOPER_MODE=="true")
		{
			//echo "IF";

			stream_context_set_option($ctx, 'ssl', 'local_cert', 'Certificates.pem');
			stream_context_set_option($ctx, 'ssl', 'passphrase', 'hello_123');
			$fp = stream_socket_client( 'ssl://gateway.sandbox.push.apple.com:2195', $err, $errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);
		}
		else
		{
			//echo "ELSE";

			stream_context_set_option($ctx, 'ssl', 'local_cert', 'CertificatesLive.pem');
			stream_context_set_option($ctx, 'ssl', 'passphrase', 'hello_123');
			$fp = stream_socket_client( 'ssl://gateway.push.apple.com:2195', $err, $errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);
		}
		
		if (!$fp)
			exit("Failed To Connect: $err - $errstr" . PHP_EOL);
		
		$body['aps'] = array('alert' => $message,'sound' => 'default','badge'=>1,'content-available'=>1);
			$body['data']=$data;
		
		$payload = json_encode($body);		
		
		$msg = chr(0) . pack('n', 32) . pack('H*', $deviceToken) . pack('n', strlen($payload)) . $payload;
		
		$result = fwrite($fp, $msg, strlen($msg));
		
		//echo $result."<br>";
		
		$res_val=0;			
		if (!$result)			
			$res_val=0;
		else			
			$res_val=1;	
			
		fclose($fp);		
		
		return $res_val;
	}

	function sendApplePushNotification_arr($deviceToken, $message)	
	{
		$ctx = stream_context_create();
		
		if(DEVELOPER_MODE=="true")
		{
			//echo "IF";

			stream_context_set_option($ctx, 'ssl', 'local_cert', 'Certificates.pem');
			stream_context_set_option($ctx, 'ssl', 'passphrase', 'hello_123');
			$fp = stream_socket_client( 'ssl://gateway.sandbox.push.apple.com:2195', $err, $errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);
		}
		else
		{
			//echo "ELSE";

			stream_context_set_option($ctx, 'ssl', 'local_cert', 'CertificatesLive.pem');
			stream_context_set_option($ctx, 'ssl', 'passphrase', 'hello_123');
			$fp = stream_socket_client( 'ssl://gateway.push.apple.com:2195', $err, $errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);
		}
		
		if (!$fp)
			exit("Failed To Connect: $err - $errstr" . PHP_EOL);
		
		foreach($deviceToken as $device_id) 
		{

			$body['aps'] = array('alert' => $message,'sound' => 'default','badge'=>1,'content-available'=>1);
			$body['data']=array();
			
			$payload = json_encode($body);		
			
			$msg = chr(0) . pack('n', 32) . pack('H*', $device_id) . pack('n', strlen($payload)) . $payload;
			
			$result = fwrite($fp, $msg, strlen($msg));
		}
		
		$res_val=0;			
		if (!$result)			
			$res_val=0;
		else			
			$res_val=1;	
			
		fclose($fp);		
		
		return $res_val;	
	}


	
function mt_rand_str ($l, $c = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890') {
    for ($s = '', $cl = strlen($c)-1, $i = 0; $i < $l; $s .= $c[mt_rand(0, $cl)], ++$i);
    return $s;
}







function upload_image($file,$path)
{


		if(isset($file["tmp_name"]))
		{
			$typ = $file['type'];
			$image_info = getimagesize($file["tmp_name"]);
			$image_width = $image_info[0];
			$image_height = $image_info[1];
			$path_parts = pathinfo($file["name"]);
			$extension = $path_parts['extension'];
		    if($typ == "image/jpeg" || $typ =="image/jpg" ||  $typ =="image/png" ||  $typ =="image/gif" || $typ == "application/octet-stream" )
		        {
		              $uploaddir = $path;
		              $name=date('Y-m-d-H-i-s')."-".rand().".".$extension;
		              $uploadimages = $uploaddir.basename($name);
		            if(move_uploaded_file(strip_tags($file['tmp_name']), $uploadimages))
		            {
		              $profile_image=$name;
		              $msg="Success uploaded";
		            }else{
		            	$profile_image='default.jpg';
		            	$msg="Not uploaded";
		            }
		      }
		        else
            {
              $profile_image='default.jpg';
              $msg="Invalid image type.";
            }
		}
		else
		{
			$profile_image='default.jpg';
			$msg="Not set as file";
		}
		$arr=array();
		$arr['error']=$msg;
		$arr['name']=$profile_image;
		return $arr;

}// upload images


function send_email($to,$msg,$subject,$lan=1,$attachment=array())
{
	
	include "smtp/email.config.php";

  
  	$mail = new PHPMailer;
    //$mail->SMTPDebug = 2;
    $mail->isSMTP();  
    $mail->CharSet = 'UTF-8';          
    $mail->Host = $userprofile['host'] ; //"smtp.gmail.com";
    $mail->SMTPAuth = true;                          
    $mail->Username = $userprofile['user'] ;
    $mail->Password =  $userprofile['password'] ;
    $mail->SMTPSecure = "ssl";
    $mail->Port = $userprofile['port'] ;
    $mail->From = $userprofile['sendfrom'];
    $mail->FromName = $userprofile['sendname'];
    $mail->isHTML(true);
    $mail->Subject = $subject;
	//language=1=>Eng,2=>Heb
	//if($lan==1)			
		$msg=get_email_template_eng($msg);
	//else
//		$msg=get_email_template($msg);
    $mail->Body = $msg;

    if($attachment!="")
	{
		//$arr_attachment=explode(",",$attachment);
		foreach ($attachment as $key => $value) {
			$mail->AddAttachment("errors/".$value);
		}
	}
    $mail->AddAddress($to);
	//$mail->AddAddress("mayur@smart-webtech.com");
    $mail->AddBCC("nilesh@smart-webtech.com");
  	if($mail->send())
    {
    	$status = "1";
    	$msg = "Sent email.";
  	}
  	else
  	{
    	$status = "0";
    	$msg = "Error occur in send email.";
  	}
return  array("msg"=>$msg,"status"=>$status);
  


}// send_email






function get_email_template_eng($data)
{

		$message="<html>
				<head><meta charset='UTF-8'><meta http-equiv='content-type' content='text/html; charset=UTF-8'></head>
				<body>
				<table cellpadding='0' cellspacing='0' border='0' width='100%' bgcolor='#404040'>
				<tr>
					<td align='center'>
						<table cellpadding='0' cellspacing='0' border='0' width='600' style='margin-top:25px;'>
						<tr>
							<table cellpadding='0' cellspacing='0' border='0' width='600' style='border:1px solid #c7c7c7;'>
							<tr>
								<td width='100%' bgcolor='#CCCCCC' height='75' style='font-family:Arial;font-size:1.2em;font-weight:bold;color:#ffffff;padding-left:25px;'>
								</td>
							</tr>
							<tr>
								<td width='100%' bgcolor='#ffffff' style='padding:25px;'>
									<table width='550' cellpadding='0' cellspacing='0' border='0'>
									<tr>
										<td style='padding-bottom:25px;border-bottom:1px solid #c7c7c7;text-align: justify;text-align-last: left;'>
											<p style='font-family:Arial;font-size:1.5em;color:#5a5a5a;line-height:1.5em;'>
												$data
											</p>
										</td>
									</tr>
									<tr>
										<td style='padding-top:25px;border-top:1px solid #c7c7c7;'></td>
									</tr>
									</table>
								</td>
							</tr>
							</table>
						</tr>
						<tr>
							<td width='100%' style='text-align:center;padding-top:10px;font-family:Arial;font-size:0.75em;color:white;line-height:1.7em;padding-bottom:25px;'>
							</td>
						</tr>
						</table>
					</td>
				</tr>
				</table>
				</body>
				</html>";
				return $message;


}// get email template for english



function getDistance( $lat1, $lon1, $lat2, $lon2, $unit) {  
    if (($lat1 == $lat2) && ($lon1 == $lon2)) {
    return 0;
  }
  else {
    $theta = $lon1 - $lon2;
    $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
    $dist = acos($dist);
    $dist = rad2deg($dist);
    $miles = $dist * 60 * 1.1515;
    $unit = strtoupper($unit);

    if ($unit == "K") {
      return ($miles * 1.609344);
    } else if ($unit == "N") {
      return ($miles * 0.8684);
    } else {
      return $miles;
    }
  } 
}

function getDistance1( $latitude1, $longitude1, $latitude2, $longitude2 ) {  
    $earth_radius = 6371;

    $dLat = deg2rad( $latitude2 - $latitude1 );  
    $dLon = deg2rad( $longitude2 - $longitude1 );  

    $a = sin($dLat/2) * sin($dLat/2) + cos(deg2rad($latitude1)) * cos(deg2rad($latitude2)) * sin($dLon/2) * sin($dLon/2);  
    $c = 2 * asin(sqrt($a));  
    $d = $earth_radius * $c;  

    return $d;  
}

    


function get_client_ip() {
    $ip = '';
     
    if (!empty($_SERVER['HTTP_CLIENT_IP']))   //check ip from share internet
    {
	$ip.=" 1.".$_SERVER['HTTP_CLIENT_IP'];
    }
    
    if (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))   //to check ip is pass from proxy
    {
	$ip.=" 2.".$_SERVER['HTTP_X_FORWARDED_FOR'];
    }
	// check if isset REMOTE_ADDR and != empty
    if(isset($_SERVER['REMOTE_ADDR']) && ($_SERVER['REMOTE_ADDR'] != '') && ($_SERVER['REMOTE_ADDR'] != NULL))
    {
    	$ip.=" 3.";
	// you're probably on localhost
    }

    return $_SERVER['REMOTE_ADDR'];
}// get_client_ip

function check_isset($fields)
{
	global $db;
	global $app_token;
	
	foreach ($fields as $value) {
		if(!isset($_REQUEST[$value]))
			return false;
	}
	return true;

}// check isset

function get_request_protect_data($fields)
{
	global $db;
	$postdata=array();
	foreach ($fields as $value) {
		$postdata[$value]=$db->real_escape_string($_REQUEST["$value"]);
	}
	return $postdata;
}// get_request_protect_data


function get_all_data_protected($post)
{
	global $db;
	$postdata=array();
	foreach ($post as $key => $value) {
		$postdata[$key]=$db->real_escape_string($post[$key]);
	}
	return $postdata;
}




function string_decrypt($text, $key) {
    return mcrypt_decrypt(
                    MCRYPT_RIJNDAEL_128, 
                    $key, 
                    base64_decode($text), 
                    MCRYPT_MODE_ECB
                    );
}

function string_encrypt($string, $key) {

  $block = mcrypt_get_block_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_ECB);
  $padding = $block - (strlen($string) % $block);
  $string .= str_repeat(chr($padding), $padding);

    $crypted_text = mcrypt_encrypt(
                            MCRYPT_RIJNDAEL_128, 
                            $key, 
                            $string, 
                            MCRYPT_MODE_ECB
                        );
    return base64_encode($crypted_text);
}

function string_encrypt_old($string, $key) {
    $crypted_text = mcrypt_encrypt(
                            MCRYPT_RIJNDAEL_128, 
                            $key, 
                            $string, 
                            MCRYPT_MODE_ECB
                        );
    return base64_encode($crypted_text);
}

?>