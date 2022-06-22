<?php 
define("_YOYO", true);
  require_once("../../../init.php");
  
 $user_id = $_POST['usid'];
 
$sql_pre = "SELECT message FROM sh_sf_users_notice WHERE uid = $user_id";
$result = Db::run()->pdoQuery($sql_pre);
$msg = $result->aResults[0]->message;
	if(!empty($msg)){
		echo $msg;
	}else{
		echo 'Type Message';
	}


