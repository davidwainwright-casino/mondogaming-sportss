<?php
include_once('../db.php');
//update 1 to admin column means records disabled for admin but available for users
mysqli_query($conn,"UPDATE sh_slot_casino_dealers SET admin=1");

if (mysqli_affected_rows($conn) > 0){
	echo 'Success!';
}else{
	echo 'No data is being affected';
}