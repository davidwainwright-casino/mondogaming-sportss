<?php include_once('../db.php');
if(isset($_POST['method']) && $_POST['method'] == 'sherr'){
 $usid = $_POST['usid'];
 $paytype = 'A2A Transfer';
 $amount = $_POST['amount'];
 $ref = $_POST['ref'];
 $date = time();
 $refd = substr(str_shuffle("TROMUXQWFG48URPLEE945Y"), 0, 20); 
 
 $cq = "SELECT * FROM users WHERE id ='$usid' AND type='Sagent'";
 $cqres = mysqli_query($conn, $cq);
 $check = $cqres->fetch_assoc();
 $bal = $check['chips'];
 if($bal < $amount){
	 echo 'Err!! You don\'t have sufficient balance. Please make a deposit to transfer.';
	   die();	   
 }
  
   if ($cqres->num_rows < 1){
	   echo 'Err!! You must be an exchange member to transfer credits.';
	   die();	   
   }
 
 
 $query = "SELECT * FROM users WHERE email = '$ref'";
  $result = mysqli_query($conn, $query);
  $tes = $result->fetch_assoc();
  $usr_id = $tes['id'];
   if ($result->num_rows < 1){
	   echo 'Err!! This email doesn\'t exist.';
	   die();	   
   } else {
	 
	 $broker_update ="UPDATE users SET chips=chips - $amount WHERE id='$usid'";
	 $broker_insert ="INSERT INTO `sh_sf_transfers` (`user_id`, `transaction_id`, `amount`, `date`, `status`, `send_to`) VALUES ($usid,'$refd','$amount','$date', 'Delivered', '$ref')";
	 
	 mysqli_query($conn,$broker_update);
	
    if ($conn->query($broker_insert) === TRUE){	
	 $user_update ="UPDATE users SET chips=chips + $amount WHERE email='$ref'";
	 $user_insert ="INSERT INTO `sh_sf_deposits` (`user_id`, `transaction_id`, `amount`, `date`, `type`, `status`) VALUES ($usr_id,'$refd','$amount','$date', 'A2A Transfer', 'Received')";
	 mysqli_query($conn,$user_update);
	 mysqli_query($conn,$user_insert);
	  echo '<i class="icon check all"></i> Money Successfully sent!';
	}
   }
}?>