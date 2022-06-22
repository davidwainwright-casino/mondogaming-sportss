<?php 
include_once('../db.php');

 $user_id = $_POST['usid'];
 $sg = $_POST['msg'];
 $msg = mysqli_real_escape_string($conn, $sg);
 $dt = time();

 
	mysqli_query($conn,"INSERT INTO sh_sf_users_notice (uid, message, timer, seen) VALUES ($user_id, '$msg', $dt, 'no') ON DUPLICATE KEY UPDATE message = '$msg', timer=$dt, seen='no'");
	
	

	
  if(mysqli_affected_rows($conn) > 0){
	       echo 'Updated Successfully';
    	   }else {
            echo "Couldn't Update";
     }


