<?php include_once("../db.php"); //error_reporting(0);

$usid = $_POST['usid'];
$types = $_POST['types'];
if($types=='bankName'){
	$type = 'bank_name';
	$suc = 'Bank Name';
}else{
	$type= 'wallet_address';
	$suc = 'Account Number';
}
$kvalue = $_POST['kvalue'];

$wcc = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM sh_sf_users_bank WHERE user_id= ".$usid.""));
	  $ifuser = $wcc['user_id'];
	  if(!empty($ifuser)){
		  mysqli_query($conn,"UPDATE sh_sf_users_bank SET $type='$kvalue' WHERE user_id=$usid");
		  if(mysqli_affected_rows($conn) > 0){
			  echo '<b><i class="icon check all"></i> '.$suc. ' Successfully Updated!</b>';
		  }else{
			  echo 'Could NOT update';
		  }
		  
	  }else{
		  mysqli_query($conn,"INSERT INTO `sh_sf_users_bank` (`user_id`, `bank_name`, `wallet_address`) VALUES ($usid, '$kvalue', '$kvalue')");
		  if(mysqli_affected_rows($conn) > 0){
			  echo '<b><i class="icon check all"></i> Successfully Updated!</b>';
		  }else{
			  echo 'Could NOT update';
		  }
		  
	  }
