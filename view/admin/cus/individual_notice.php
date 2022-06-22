<?php 
define("_YOYO", true);
  require_once("../../../init.php");
  
 $user_id = $_POST['usid'];
 $mg = addslashes($_POST['msg']);
 $msg = mysqli_real_escape_string($_POST['msg']);
 $dt = time();

 
	$i_record = "INSERT INTO sh_sf_users_notice (uid, message, timer, seen) VALUES ($user_id, '$msg', $dt, 'no') ON DUPLICATE KEY UPDATE message = '$msg', timer=$dt, seen='no'";
	$iresult = Db::run()->pdoQuery($i_record);
	
  if ($iresult) {
	       echo 'Updated Successfully';
    	   }else {
            echo "Couldn't Update";
     }


