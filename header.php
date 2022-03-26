<?php function get_header($data)
{ ?>
<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="assets/css/bootstrap.css" >
    <style type="text/css">
    	.error_alert{
    		margin-top: 15px;
    	}
    </style>
    <title><?php if(isset($data['title'])){ echo $data['title']; } ?></title>
  </head>
  <body>
<?php } ?>